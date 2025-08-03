<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Files;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $originalFilePath = auth()->user()->files->location;
        $backupFilePath = $originalFilePath . '.backup';

        if (Storage::disk('private')->exists($originalFilePath)) {
            // Backup file asli
            Storage::disk('private')->move($originalFilePath, $backupFilePath);
        }

        try {
            $userId = auth()->user()->id;
            $request->validate([
                'nama_lengkap' => ['required'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'not_regex:/its\.ac\.id/i',
                    function ($attribute, $value, $fail) use ($userId) {
                        $hash = hash('sha256', strtolower($value));
                        if (\App\Models\User::where('email_hash', $hash)->where('id', '!=', $userId)->exists()) {
                            $fail('Email sudah digunakan.');
                        }
                    }
                ],
                'email_its' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'regex:/@([a-z0-9.-]+\.)?its\.ac\.id$/i',
                    function ($attribute, $value, $fail) use ($userId) {
                        if ($value) {
                            $hash = hash('sha256', strtolower($value));
                            if (\App\Models\User::where('email_its_hash', $hash)->where('id', '!=', $userId)->exists()) {
                                $fail('Email ITS sudah digunakan.');
                            }
                        }
                    }
                ],
                'no_kartuid' => [
                    'required',
                    function ($attribute, $value, $fail) use ($userId) {
                        $hash = hash('sha256', $value);
                        if (\App\Models\User::where('kartu_id_hash', $hash)->where('id', '!=', $userId)->exists()) {
                            $fail('Kartu ID sudah digunakan.');
                        }
                    }
                ],
                'no_telepon' => [
                    'required',
                    'string',
                    'max:13',
                    function ($attribute, $value, $fail) use ($userId) {
                        $hash = hash('sha256', $value);
                        if (\App\Models\User::where('no_hp_hash', $hash)->where('id', '!=', $userId)->exists()) {
                            $fail('Nomor HP sudah digunakan.');
                        }
                    }
                ],
                'file_kartuid' => ['file', 'image', 'max:5120'],
            ],[
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'email.required'        => 'Email wajib diisi.',
                'email.string'          => 'Email harus berupa teks.',
                'email.lowercase'       => 'Email harus dalam huruf kecil.',
                'email.email'           => 'Format email tidak valid.',
                'email_its.not_regex'   => 'Tidak boleh menggunakan email ITS.',
                'email.unique'          => 'Email sudah terdaftar.',
                'email_its.required'    => 'Email ITS wajib diisi.',
                'email_its.string'      => 'Email ITS harus berupa teks.',
                'email_its.lowercase'   => 'Email ITS harus dalam huruf kecil.',
                'email_its.email'       => 'Format email tidak valid.',
                'email_its.regex'       => 'Email ITS harus menggunakan domain its.ac.id.',
                'email_its.unique'      => 'Email ITS sudah terdaftar.',
                'no_kartuid.required'   => 'Nomor kartu ID wajib diisi.',
                'no_kartuid.unique'     => 'Kartu ID sudah terdaftar.',
                'no_telepon.required'   => 'Nomor telepon wajib diisi.',
                'no_telepon.string'     => 'Nomor telepon harus berupa teks.',
                'no_telepon.max'        => 'Nomor telepon maksimal 13 karakter.',
                'no_telepon.unique'     => 'No Hp sudah terdaftar.',
                'file_kartuid.file'     => 'File kartu ID harus berupa file yang valid.',
                'file_kartuid.image'    => 'File kartu ID harus berupa gambar.',
                'file_kartuid.mimes'    => 'File kartu ID harus berformat jpeg, png, atau jpg.',
                'file_kartuid.max'      => 'Ukuran file kartu ID maksimal 5MB.',
            ]);

            DB::beginTransaction();

            if ($request->hasFile('file_kartuid')) {
                $id_file = auth()->user()->file_kartuid;
                $file = $request->file('file_kartuid');
                $fileName = $file->getClientOriginalName();
                $fileMime = $file->getClientMimeType();
                $fileExt = $file->getClientOriginalExtension();
                $newFileName = $id_file.'.'.$fileExt;
                $fileSize = $file->getSize();
                $fileContents = file_get_contents($file->getRealPath());
                $encryptedFileContents = Crypt::encrypt($fileContents);
                $filePath = 'identitas/' . $newFileName;
                Storage::disk('private')->put($filePath, $encryptedFileContents);

                //save file data ke database
                Files::where('id_file', $id_file)->update([
                    'file_name' => $fileName,
                    'location' => $filePath,
                    'mime' => $fileMime,
                    'ext' => $fileExt,
                    'file_size' => $fileSize,
                    'updated_at' => now(),
                    'updater' => auth()->user()->id
                ]);
            }

            $hashBaru = hash('sha256', strtolower($request->email));

            $user = User::find(auth()->user()->id);
            $user->email_its = $request->email_its;
            if ($user->email_hash !== $hashBaru) {
                $user->email = $request->email; // ini akan otomatis set email_hash juga
                $user->email_verified_at = null;
            }
            $user->name = $request->nama_lengkap;
            $user->kartu_id = $request->no_kartuid;
            $user->no_hp = $request->no_telepon;
            $user->save();

            DB::commit();

            if ($request->hasFile('file_kartuid')) {
                if (Storage::disk('private')->exists($backupFilePath)) {
                    Storage::disk('private')->delete($backupFilePath);
                }
            }else{
                if (Storage::disk('private')->exists($backupFilePath)) {
                    Storage::disk('private')->move($backupFilePath, $originalFilePath);
                }
            }

            return Redirect::route('profile.edit')->with('status', 'profile-updated');

        } catch (ValidationException $e) {
            DB::rollBack();
            Storage::disk('private')->delete($originalFilePath);
            Storage::disk('private')->move($backupFilePath, $originalFilePath);
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            DB::rollBack();
            Storage::disk('private')->delete($originalFilePath);
            Storage::disk('private')->move($backupFilePath, $originalFilePath);
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
