<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Files;
use Exception;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
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
            $request->validate([
                'nama_lengkap' => ['required'],
                'email' => ['required', 'string', 'lowercase', 'email', 'unique:'.User::class],
                'password' => ['required', 'confirmed', Rules\Password::defaults()],
                'no_kartuid' => ['required'],
                'no_telepon' => ['required', 'string', 'max:13'],
                'file_kartuid' => ['required', 'file', 'image', 'max:5120'],
            ],[
                'nama_lengkap.required' => 'Nama lengkap wajib diisi.',
                'email.required'        => 'Email wajib diisi.',
                'email.string'          => 'Email harus berupa teks.',
                'email.lowercase'       => 'Email harus dalam huruf kecil.',
                'email.email'           => 'Format email tidak valid.',
                'email.unique'          => 'Email sudah terdaftar.',
                'password.required'     => 'Password wajib diisi.',
                'password.confirmed'    => 'Konfirmasi password tidak cocok.',
                'no_kartuid.required'   => 'Nomor kartu ID wajib diisi.',
                'no_telepon.required'   => 'Nomor telepon wajib diisi.',
                'no_telepon.string'     => 'Nomor telepon harus berupa teks.',
                'no_telepon.max'        => 'Nomor telepon maksimal 13 karakter.',
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
            $filePath = $file->storeAs('identitas', $newFileName, 'local');

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
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'kartu_id' => $request->no_kartuid,
                'no_hp' => $request->no_telepon,
                'file_kartuid' => $id_file
            ]);

            event(new Registered($user));

            Auth::login($user);

            return redirect(route('dashboard', absolute: false));
        } catch (ValidationException $e) {
            Storage::disk('local')->delete($filePath);
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Storage::disk('local')->delete($filePath);
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
