@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-style')
    @vite([
        'resources/assets/vendor/scss/pages/page-auth.scss',
        'resources/assets/css/custom.scss'
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    <div class="alert alert-danger alert-dismissible" role="alert">
                        {{ $error }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->updatePassword->get('current_password'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    Password sekarang tidak cocok!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->updatePassword->get('password'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    Masukkan Password yang benar!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if($errors->updatePassword->get('password_confirmation'))
                <div class="alert alert-danger alert-dismissible" role="alert">
                    Password Konfirmasi tidak sama!
                </div>
            @endif
            @if (session('status') === 'profile-updated')
                <div class="alert alert-success alert-dismissible" role="alert">
                    Data Akun Berhasil diupdate!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            @if (session('status') === 'password-updated')
                <div class="alert alert-success alert-dismissible" role="alert">
                    Data Password Berhasil diubah!
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card mb-6">
                <!-- Account -->
                <h5 class="card-header pb-4 border-bottom"><span class="tf-icons bx bx-edit"></span>&nbsp;Update Data Akun</h5>
                <div class="card-body pt-4">
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                        @csrf
                        @method('patch')

                        <div class="row g-6">
                            <div>
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" value="{{ old('nama_lengkap', $user->name) }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="no_kartuid" class="form-label">Nomor Kartu ID (NRP/KTP) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_kartuid" name="no_kartuid" placeholder="Nomor Kartu ID (NRP/KTP)" value="{{ old('no_kartuid', $user->kartu_id) }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required autocomplete="off">

                                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                                    <div>
                                        <p class="text-sm mt-2 text-gray-800">
                                            {{ __('Your email address is unverified.') }}

                                            <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                {{ __('Click here to re-send the verification email.') }}
                                            </button>
                                        </p>

                                        @if (session('status') === 'verification-link-sent')
                                            <p class="mt-2 font-medium text-sm text-green-600">
                                                {{ __('A new verification link has been sent to your email address.') }}
                                            </p>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div>
                                <label for="no_telepon" class="form-label">No. Telepon <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_telepon" name="no_telepon" placeholder="contoh: 085924315876" value="{{ old('no_telepon', $user->no_hp) }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="file_kartuid" class="form-label">Unggah Kartu ID (KTM/KTP) <span class="text-danger">*</span> <span class="text-muted"><i><b>(File gambar max 5 mb)</b></i></span></label>
                                    @php
                                        $file = auth()->user()->file_kartuid;
                                        $filePath = auth()->user()->files->location;
                                        $imageUrl = Storage::disk('local')->exists($filePath)
                                            ? route('file.getprivatefile', $file)
                                            : asset('assets/img/no_image.jpg');
                                    @endphp
                                    <img src="{{ $imageUrl.'?time='.time() }}" class="d-block h-px-100 rounded">
                                    <p class="text-muted mt-4" style="font-style: italic; font-size: smaller">klik tombol dibawah untuk mengubah file!</p>
                                    <input type="file" class="form-control" id="file_kartuid" name="file_kartuid" accept="image/*">
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <!-- Account -->
                <h5 class="card-header pb-4 border-bottom"><span class="tf-icons bx bx-key"></span>&nbsp;Update Password</h5>
                <div class="card-body pt-4">
                    <form id="formUpdatePassword" method="POST" action="{{ route('password.update') }}" class="space-y-6">
                        @csrf
                        @method('put')
                        <div class="row g-6">
                            <div class="form-password-toggle">
                                <label class="form-label" for="update_password_current_password">Password Sekarang<span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="update_password_current_password" required class="form-control" name="current_password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="error-container" id="error-current"></div>
                            </div>
                            <div class="form-password-toggle">
                                <label class="form-label" for="update_password_password">Password Baru<span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="update_password_password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="error-container" id="error-password"></div>
                            </div>
                            <div class="form-password-toggle">
                                <label class="form-label" for="update_password_password_confirmation">Konfirmasi Password Baru<span class="text-danger">*</span></label>
                                <div class="input-group input-group-merge">
                                    <input type="password" id="update_password_password_confirmation" class="form-control" name="password_confirmation" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" />
                                    <span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>
                                </div>
                                <div class="error-container" id="error-konfirmasipass"></div>
                            </div>
                            <div class="mt-6">
                                <button type="submit" class="btn btn-primary me-3">Update Password</button>
                            </div>
                        </div>
                    </form>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/script_view/update_profile.js')
@endsection
