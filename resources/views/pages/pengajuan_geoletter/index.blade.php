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
                <ol class="breadcrumb flex">
                    <li class="breadcrumb-item">
                        <a href="#">Pengajuan</a>
                    </li>
                    <li class="breadcrumb-item active">Pengajuan Geo Letter</li>
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
                        <table id="datatable" class="table">
                            <thead>
                            <tr>
                                <th style="border-top-width: 1px" nowrap class="text-center">No</th>
                                <th style="border-top-width: 1px" nowrap>Jenis Surat</th>
                                <th style="border-top-width: 1px" nowrap>Pengaju</th>
                                <th style="border-top-width: 1px" nowrap>Keterangan</th>
                                <th style="border-top-width: 1px" nowrap class="text-center">Aksi</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    <ul class="fa-ul ml-auto float-end mt-5">
                        <li>
                            <small><em>Pengajuan yang sudah diajukan tidak dapat dibatalkan.</em></small>
                        </li>
                    </ul>
                </div>
                <!-- /Account -->
            </div>
        </div>
    </div>
    <div class="modal fade" id="modal-hapus" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengumuman.hapus') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengumuman" id="id_hapus">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Hapus Pengumuman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin menghapus pengumuman ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-unpost" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengumuman.unposting') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengumuman" id="id_unposting">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Batal Posting Pengumuman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin membatalkan posting pengumuman ini?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-warning">Batal Posting</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="modal fade" id="modal-post" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm" role="document">
            <form action="{{ route('pengumuman.posting') }}" method="POST">
                @csrf
                <input type="hidden" name="id_pengumuman" id="id_posting">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel2">Posting Pengumuman</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p>Apakah yakin posting pengumuman ini? posting pengumuman akan menampilkan pengumuman pada landing page.</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Posting</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        let title = "{{ $title }}";
        let routeName = "{{ route('pengajuangeoletter.getdata') }}"; // Ensure route name is valid
        let routeTambah = "{{ route('pengajuangeoletter.tambah') }}"
    </script>
    @vite('resources/views/script_view/pengajuan_geoletter/list_pengajuan.js')
@endsection
