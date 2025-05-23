<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Files;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (Storage::disk('local')->exists($originalFilePath)) {
            // Backup file asli
            Storage::disk('local')->move($originalFilePath, $backupFilePath);
        }

        try {
            $request->validate([
                'nama_lengkap' => ['required'],
                'email' => ['required', 'string', 'lowercase', 'email', Rule::unique('users', 'email')->ignore(auth()->user()->id)],
                'email_its' => ['required', 'string', 'lowercase', 'email', Rule::unique('users', 'email_its')->ignore(auth()->user()->id)],
                'no_kartuid' => ['required', Rule::unique('users', 'kartu_id')->ignore(auth()->user()->id)],
                'no_telepon' => ['required', 'string', 'max:13', Rule::unique('users', 'no_hp')->ignore(auth()->user()->id)],
                'file_kartuid' => ['file', 'image', 'max:5120'],
            ],[
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'email.required'        => 'Email wajib diisi.',
                'email.string'          => 'Email harus berupa teks.',
                'email.lowercase'       => 'Email harus dalam huruf kecil.',
                'email.email'           => 'Format email tidak valid.',
                'email.unique'          => 'Email sudah terdaftar.',
                'email_its.required'    => 'Email ITS wajib diisi.',
                'email_its.string'      => 'Email ITS harus berupa teks.',
                'email_its.lowercase'   => 'Email ITS harus dalam huruf kecil.',
                'email_its.email'       => 'Format email tidak valid.',
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
                $filePath = $file->storeAs('identitas', $newFileName, 'local');

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

            $user = User::find(auth()->user()->id);
            $user->email = $request->email;
            $user->email_its = $request->email_its;
            if ($user->isDirty('email')) {
                $user->email_verified_at = null;
            }
            $user->name = $request->nama_lengkap;
            $user->kartu_id = $request->no_kartuid;
            $user->no_hp = $request->no_telepon;
            $user->save();

            DB::commit();

            if ($request->hasFile('file_kartuid')) {
                if (Storage::disk('local')->exists($backupFilePath)) {
                    Storage::disk('local')->delete($backupFilePath);
                }
            }else{
                if (Storage::disk('local')->exists($backupFilePath)) {
                    Storage::disk('local')->move($backupFilePath, $originalFilePath);
                }
            }

            return Redirect::route('profile.edit')->with('status', 'profile-updated');

        } catch (ValidationException $e) {
            DB::rollBack();
            Storage::disk('local')->delete($originalFilePath);
            Storage::disk('local')->move($backupFilePath, $originalFilePath);
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            DB::rollBack();
            Storage::disk('local')->delete($originalFilePath);
            Storage::disk('local')->move($backupFilePath, $originalFilePath);
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
