<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AksesUser;
use App\Models\User;
use App\Models\Files;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;
use Ramsey\Uuid\Nonstandard\Uuid;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        try {
            $filePath = null; // inisialisasi

            $request->validate([
                'nama_lengkap' => ['required'],
                'email' => [
                    'required',
                    'string',
                    'lowercase',
                    'email',
                    'not_regex:/its\.ac\.id/i', // pastikan bukan email ITS
                    function ($attribute, $value, $fail) {
                        $hash = hash('sha256', strtolower($value));
                        if (\App\Models\User::where('email_hash', $hash)->exists()) {
                            $fail('Email sudah terdaftar.');
                        }
                    }
                ],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'no_kartuid' => [
                    'required',
                    function ($attribute, $value, $fail) {
                        $hash = hash('sha256', $value);
                        if (\App\Models\User::where('kartu_id_hash', $hash)->exists()) {
                            $fail('Kartu ID sudah terdaftar.');
                        }
                    }
                ],
                'no_telepon' => [
                    'required',
                    'string',
                    'max:13',
                    function ($attribute, $value, $fail) {
                        $hash = hash('sha256', $value);
                        if (\App\Models\User::where('no_hp_hash', $hash)->exists()) {
                            $fail('Nomor telepon sudah terdaftar.');
                        }
                    }
                ],
                'file_kartuid' => ['required', 'file', 'image', 'max:5120'],
            ],[
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'email.required'        => 'Email wajib diisi.',
                'email.string'          => 'Email harus berupa teks.',
                'email.lowercase'       => 'Email harus dalam huruf kecil.',
                'email.email'           => 'Format email tidak valid.',
                'email_its.not_regex'   => 'Tidak boleh menggunakan email ITS.',
                'email.unique'          => 'Email sudah terdaftar.',
                'password.required'     => 'Password wajib diisi.',
                'password.confirmed'    => 'Konfirmasi password tidak cocok.',
                'no_kartuid.required'   => 'Nomor kartu ID wajib diisi.',
                'no_kartuid.unique'     => 'Kartu ID sudah terdaftar.',
                'no_telepon.required'   => 'Nomor telepon wajib diisi.',
                'no_telepon.string'     => 'Nomor telepon harus berupa teks.',
                'no_telepon.max'        => 'Nomor telepon maksimal 13 karakter.',
                'no_telepon.unique'     => 'No Hp sudah terdaftar.',
                'file_kartuid.required' => 'File kartu ID wajib diunggah.',
                'file_kartuid.file'     => 'File kartu ID harus berupa file yang valid.',
                'file_kartuid.image'    => 'File kartu ID harus berupa gambar.',
                'file_kartuid.mimes'    => 'File kartu ID harus berformat jpeg, png, atau jpg.',
                'file_kartuid.max'      => 'Ukuran file kartu ID maksimal 5MB.',
            ]);

            $id_file = strtoupper(Uuid::uuid4()->toString());
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

            DB::beginTransaction();

            //save file data ke database
            Files::create([
                'id_file' => $id_file,
                'file_name' => $fileName,
                'location' => $filePath,
                'mime' => $fileMime,
                'ext' => $fileExt,
                'file_size' => $fileSize,
                'created_at' => now(),
            ]);

            //save user ke database
            $user = User::create([
                'name' => $request->nama_lengkap,
                'email' => $request->email, // akan otomatis terenkripsi oleh mutator
                'password' => Hash::make($request->password),
                'kartu_id' => $request->no_kartuid, // otomatis terenkripsi
                'no_hp' => $request->no_telepon,     // otomatis terenkripsi
                'file_kartuid' => $id_file
            ]);

            //tambah akses user ke database
            $id_user = $user->id;
            AksesUser::create([
                'id_akses' => 8,
                'id_user' => $id_user,
                'is_default' => 1,
                'created_at' => now(),
            ]);

            DB::commit();
            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (ValidationException $e) {
            DB::rollBack();
            if ($filePath) {
                Storage::disk('private')->delete($filePath);
            }
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            DB::rollBack();
            if ($filePath) {
                Storage::disk('private')->delete($filePath);
            }
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
