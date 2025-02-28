@extends('layouts/contentNavbarLayout')

@section('title', 'Account settings - Account')

@section('page-script')
    @vite([
        'resources/assets/js/pages-account-settings-account.js',
        'resources/assets/css/custom.scss'
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card mb-6">
                <!-- Account -->
                <h5 class="card-header pb-4 border-bottom"><span class="tf-icons bx bx-edit"></span>&nbsp;Update Akun</h5>
                <div class="card-body pt-4">
                    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
                        @csrf
                    </form>

                    <form id="formAccountSettings" method="POST" action="{{ route('profile.update') }}">
                        @csrf
                        @method('patch')

                        <div class="row g-6">
                            <div>
                                <label for="nama_lengkap" class="form-label">Nama Lengkap <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" placeholder="Nama Lengkap" value="{{ old('nama_lengkap', $user->name) }}" autofocus required autocomplete="off">
                            </div>
                            <div>
                                <label for="no_kartuid" class="form-label">Nomor Kartu ID (NRP/KTP) <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="no_kartuid" name="no_kartuid" placeholder="Nomor Kartu ID (NRP/KTP)" value="{{ old('no_kartuid', $user->kartu_id) }}" required autocomplete="off">
                            </div>
                            <div>
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{ old('email', $user->email) }}" required autocomplete="off">
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />

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
                                <input type="file" class="form-control" id="file_kartuid" name="file_kartuid" accept="image/*" required>
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
@endsection

