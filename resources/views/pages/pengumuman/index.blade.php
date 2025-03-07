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
            <div class="card mb-6">
                <!-- Account -->
                <div class="card-header header-elements pb-4 border-bottom">
                    <h5 class="mb-0 me-2"><span class="tf-icons bx bx-list-check"></span>&nbsp;List Pengumuman</h5>
                    <div class="card-header-elements ms-auto">
                        <button type="button" class="btn btn-xs btn-primary"><span class="icon-base bx bx-plus icon-xs me-1"></span>Tambah Pengumuman</button>
                    </div>
                </div>
                <div class="card-body pt-4">
                    <table id="datatable" class="datatables-basic table table-bordered">
                        <thead>
                        <tr>
                            <th></th>
                            <th></th>
                            <th>id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Date</th>
                            <th>Salary</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    @vite('resources/views/script_view/list_pengumuman.js')
@endsection
