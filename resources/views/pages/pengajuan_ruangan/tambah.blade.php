@php use Illuminate\Support\Facades\Storage; @endphp
@extends('layouts/contentNavbarLayout')

@section('title', $title.' • '.config('variables.templateName'))

@section('page-style')
    @vite([
        'resources/assets/vendor/scss/pages/page-auth.scss',
        'resources/assets/css/custom.scss'
    ])
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="#">Pengajuan</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ route('pengajuanruangan') }}">{{ (!empty(config('variables.namaLayananSewaRuangan')) ? config('variables.namaLayananSewaRuangan') : '') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ $title }}</li>
                </ol>
            </nav>
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
            @if (session('success'))
                <div class="alert alert-success alert-dismissible" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-plus mb-1"></i>&nbsp;Tambah Pengajuan</h5>
                    <a href="{{ route('pengajuanruangan') }}" class="btn btn-sm btn-secondary btn-sm">
                        <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                    </a>
                </div>
            </div>
            <div id="#wizard" class="bs-stepper mt-2 linear">
                <div class="bs-stepper-header">
                    <div class="step active" data-target="#account-details-validation">
                        <button type="button" class="step-trigger" aria-selected="true">
                            <span class="bs-stepper-circle">1</span>
                            <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Account Details</span>
                                    <span class="bs-stepper-subtitle">Setup Account Details</span>
                                    </span>
                        </button>
                    </div>
                    <div class="line">
                        <i class="icon-base bx bx-chevron-right icon-md"></i>
                    </div>
                    <div class="step" data-target="#personal-info-validation">
                        <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
                            <span class="bs-stepper-circle">2</span>
                            <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Personal Info</span>
                                    <span class="bs-stepper-subtitle">Add personal info</span>
                                    </span>
                        </button>
                    </div>
                    <div class="line">
                        <i class="icon-base bx bx-chevron-right icon-md"></i>
                    </div>
                    <div class="step" data-target="#social-links-validation">
                        <button type="button" class="step-trigger" aria-selected="false" disabled="disabled">
                            <span class="bs-stepper-circle">3</span>
                            <span class="bs-stepper-label">
                                    <span class="bs-stepper-title">Social Links</span>
                                    <span class="bs-stepper-subtitle">Add social links</span>
                                    </span>
                        </button>
                    </div>
                </div>
                <div class="bs-stepper-content">
                    <form id="wizard-validation" action="{{ route('pengajuanruangan.dotambah') }}" onsubmit="return false">
                        <div id="account-details-validation" class="content active dstepper-block fv-plugins-bootstrap5 fv-plugins-framework">
                            <div class="content-header mb-4">
                                <h6 class="mb-0">Account Details</h6>
                                <small>Enter Your Account Details.</small>
                            </div>
                            <div class="row g-6">
                                <div class="col-sm-6 form-control-validation fv-plugins-icon-container">
                                    <label class="form-label" for="formValidationUsername">Username</label>
                                    <input type="text" name="formValidationUsername" id="formValidationUsername" class="form-control" placeholder="johndoe">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
                                <div class="col-sm-6 form-control-validation fv-plugins-icon-container">
                                    <label class="form-label" for="formValidationEmail">Email</label>
                                    <input type="email" name="formValidationEmail" id="formValidationEmail" class="form-control" placeholder="john.doe@email.com" aria-label="john.doe">
                                    <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div></div>
                                <div class="col-sm-6 form-control-validation form-password-toggle fv-plugins-icon-container">
                                    <label class="form-label" for="formValidationPass">Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input type="password" id="formValidationPass" name="formValidationPass" class="form-control" placeholder="············" aria-describedby="formValidationPass2">
                                        <span class="input-group-text cursor-pointer" id="formValidationPass2"><i class="icon-base bx bx-hide"></i></span>
                                    </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="col-sm-6 form-control-validation form-password-toggle fv-plugins-icon-container">
                                    <label class="form-label" for="formValidationConfirmPass">Confirm Password</label>
                                    <div class="input-group input-group-merge has-validation">
                                        <input type="password" id="formValidationConfirmPass" name="formValidationConfirmPass" class="form-control" placeholder="············" aria-describedby="formValidationConfirmPass2">
                                        <span class="input-group-text cursor-pointer" id="formValidationConfirmPass2"><i class="icon-base bx bx-hide"></i></span>
                                    </div><div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
                                </div>
                                <div class="col-12 d-flex justify-content-between">
                                    <button class="btn btn-label-secondary btn-prev" disabled="">
                                        <i class="icon-base bx bx-chevron-left icon-sm ms-sm-n2 me-sm-2"></i>
                                        <span class="align-middle d-sm-inline-block d-none">Previous</span>
                                    </button>
                                    <button class="btn btn-primary btn-next">
                                        <span class="align-middle d-sm-inline-block d-none me-sm-2">Next</span>
                                        <i class="icon-base bx bx-chevron-right icon-sm me-sm-n2"></i>
                                    </button>
                                </div>
                                <div class="col-12">
                                    <ul class="fa-ul ml-auto float-end mt-5">
                                        <li>
                                            <small><em>Jadwal yang tersedia adalah <b>H + 1</b> dari waktu pengajuan.</em></small>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
{{--    <div class="modal modal-transparent fade" id="modals-transparent" tabindex="-1" style="border: none;">--}}
{{--        <div class="modal-dialog modal-lg">--}}
{{--            <div class="modal-content" style="background: rgba(0, 0, 0, 0);border: none;color: white;">--}}
{{--                <div class="modal-body">--}}
{{--                    <img id="kartu_idmodal" src="{{ $imageUrl }}" class="img-fluid w-100 h-100 object-fit-cover" alt="kartu ID">--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
@endsection
@section('page-script')
    <script>
        let routeGetJenisSurat = "{{ route('pengajuansurat.getjenissurat') }}";
    </script>
    @vite('resources/views/script_view/pengajuan_ruangan/tambah_pengajuan.js')
@endsection
