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
                        <a href="{{ route('pengajuansurat') }}">{{ (!empty(config('variables.namaLayananPersuratan')) ? config('variables.namaLayananPersuratan') : '') }}</a>
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
            <div class="row">
                <div class="col-md-8">
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                            <h5 class="card-title mb-0"><i class="bx bx-edit-alt"></i>&nbsp;Data Pemohon</h5>
                            <a href="{{ route('pengajuansurat') }}" class="btn btn-sm btn-secondary btn-sm">
                                <i class="bx bx-arrow-back"></i>&nbsp;Kembali
                            </a>
                        </div>
                        <div class="card-body pt-4">
                            <div class="row g-6">
                                <div>
                                    <label for="nama_pengaju" class="form-label">Nama Pengaju <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->name }}" readonly>
                                </div>
                                <div>
                                    <label for="kartu_id" class="form-label">Nomor Kartu ID (NRP/KTP) <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->kartu_id }}" readonly>
                                </div>
                                <div>
                                    <label for="file_kartu_id" class="form-label">File Kartu ID (NRP/KTP) <span
                                            class="text-danger">*</span></label>
                                    @php
                                        $file = auth()->user()->file_kartuid;
                                        $filePath = auth()->user()->files->location;
                                        $imageUrl = Storage::disk('local')->exists($filePath)
                                            ? route('file.getprivatefile', $file)
                                            : asset('assets/img/no_image.jpg');
                                    @endphp
                                    <div class="d-flex align-items-center gap-2">
                                        <img src="{{ $imageUrl }}" class="d-block h-px-100 rounded">
                                        <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                                data-bs-target="#modals-transparent">
                                            Lihat file
                                        </button>
                                    </div>
                                </div>
                                <div>
                                    <label for="no_hp" class="form-label">No. Hp <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->no_hp }}" readonly>
                                </div>
                                <div>
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" value="{{ Auth()->user()->email }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card mb-6">
                        <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                            <h5 class="card-title mb-0"><i class="bx bx-edit-alt"></i>&nbsp;Histori Persetujuan</h5>
                        </div>
                        <div class="card-body pt-4">
                            <ul class="timeline-with-icons">
                                <li class="timeline-item mb-5">
                                    <span class="timeline-icon bg-success"><i class="bx bx-check text-white"></i></span>
                                    <h5 class="fw-bold">Our company starts its operations</h5>
                                    <p class="text-muted mb-2 fw-bold">11 March 2020</p>
                                    <p class="text-muted">
                                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Sit
                                        necessitatibus adipisci, ad alias, voluptate pariatur officia
                                        repellendus repellat inventore fugit perferendis totam dolor
                                        voluptas et corrupti distinctio maxime corporis optio?
                                    </p>
                                </li>

                                <li class="timeline-item mb-5">

      <span class="timeline-icon">
        <i class="fas fa-hand-holding-usd text-primary fa-sm fa-fw"></i>
      </span>
                                    <h5 class="fw-bold">First customer</h5>
                                    <p class="text-muted mb-2 fw-bold">19 March 2020</p>
                                    <p class="text-muted">
                                        Quisque ornare dui nibh, sagittis egestas nisi luctus nec. Sed
                                        aliquet laoreet sapien, eget pulvinar lectus maximus vel.
                                        Phasellus suscipit porta mattis.
                                    </p>
                                </li>

                                <li class="timeline-item mb-5">

      <span class="timeline-icon">
        <i class="fas fa-users text-primary fa-sm fa-fw"></i>
      </span>
                                    <h5 class="fw-bold">Our team exceeds 10 people</h5>
                                    <p class="text-muted mb-2 fw-bold">24 June 2020</p>
                                    <p class="text-muted">
                                        Orci varius natoque penatibus et magnis dis parturient montes,
                                        nascetur ridiculus mus. Nulla ullamcorper arcu lacus, maximus
                                        facilisis erat pellentesque nec. Duis et dui maximus dui aliquam
                                        convallis. Quisque consectetur purus erat, et ullamcorper sapien
                                        tincidunt vitae.
                                    </p>
                                </li>

                                <li class="timeline-item mb-5">

      <span class="timeline-icon">
        <i class="fas fa-money-bill-wave text-primary fa-sm fa-fw"></i>
      </span>
                                    <h5 class="fw-bold">Earned the first million $!</h5>
                                    <p class="text-muted mb-2 fw-bold">15 October 2020</p>
                                    <p class="text-muted">
                                        Nulla ac tellus convallis, pulvinar nulla ac, fermentum diam. Sed
                                        et urna sit amet massa dapibus tristique non finibus ligula. Nam
                                        pharetra libero nibh, id feugiat tortor rhoncus vitae. Ut suscipit
                                        vulputate mattis.
                                    </p>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card mb-6">
                <div class="card-header d-flex justify-content-between align-items-center pb-4 border-bottom">
                    <h5 class="card-title mb-0"><i class="bx bx-edit-alt"></i>&nbsp;Data Persuratan</h5>
                </div>
                <div class="card-body pt-4">
                    <form id="formPengajuan" method="POST" action="{{ route('pengajuansurat.doupdate') }}">
                        @csrf
                        <div class="row g-6">
                            <div>
                                <label for="jenis_surat" class="form-label">Jenis Surat <span
                                        class="text-danger">*</span></label>
                                <select name="jenis_surat" id="jenis_surat" class="form-control" required autofocus>
                                    <option value="" selected disabled>-- Pilih Jenis Surat --</option>
                                    @foreach($dataJenisSurat as $row)
                                        <option value="{{ $row->id_jenissurat }}">{{ $row->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label for="isi_surat" class="form-label">Form Isi Surat <span
                                        class="text-danger">*</span></label>
                                <div id="editor_surat" style="height: 250px;"></div>
                                <input type="hidden" name="editor_quil" id="editor_quil">
                                <div class="error-container" id="error-quil"></div>
                            </div>
                            <div>
                                <label for="keterangan" class="form-label">Keterangan <span class="text-danger">*</span></label>
                                <textarea name="keterangan" id="keterangan" class="form-control" cols="10" rows="5"
                                          required></textarea>
                            </div>
                        </div>
                        <div class="mt-6">
                            <button type="submit" class="btn btn-primary me-3">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal modal-transparent fade" id="modals-transparent" tabindex="-1" style="border: none;">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" style="background: rgba(0, 0, 0, 0);border: none;color: white;">
                <div class="modal-body">
                    <img id="kartu_idmodal" src="{{ $imageUrl }}" class="img-fluid w-100 h-100 object-fit-cover" alt="kartu ID">
                </div>
            </div>
        </div>
    </div>
@endsection
@section('page-script')
    <script>
        let routeGetJenisSurat = "{{ route('pengajuansurat.getjenissurat') }}";
    </script>
    @vite('resources/views/script_view/pengajuan_surat/detail_pengajuan.js')
@endsection
