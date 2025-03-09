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
                        <a href="#">Master Data</a>
                    </li>
                    <li class="breadcrumb-item active">Pengumuman</li>
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
            <div class="card mb-6">
                <div class="card-body pt-4">
                    <div class="table-responsive">
                        <table id="datatable" class="datatables-basic table table-bordered">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>Judul</th>
                                <th>Author</th>
                                <th>Tanggal Post</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        let routeName = "{{ route('pengumuman.getdata') }}"; // Ensure route name is valid
        let routeTambah = "{{ route('pengumuman.tambah') }}"
    </script>
    @vite('resources/views/script_view/list_pengumuman.js')
@endsection
