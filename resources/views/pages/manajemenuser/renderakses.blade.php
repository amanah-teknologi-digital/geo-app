<div class="card mb-4">
    <div class="card-header"><b>Tambah Data Akses</b></div>
    <div class="card-body">
        <form action="{{ route('manajemen-user.updateakses') }}" method="POST">
            @csrf
            <input type="hidden" name="id_user" value="{{ $idUser }}">
            <div class="row g-3 align-items-center">
                <div class="col-md-6">
                    <select name="id_akses" id="id_akses" class="form-select" required>
                        <option value="" selected disabled>-- Pilih Akses --</option>
                        @foreach($listAkses as $akses)
                            <option value="{{ $akses->id_akses }}">{{ $akses->nama }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-primary"><span class="bx bx-save"></span>&nbsp;Tambah Akses</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header"><b>Daftar Akses {{ $dataUser->name }}</b></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm table-bordered" id="tabelAkses">
                <thead class="table-primary">
                <tr>
                    <th class="text-center" style="width: 10%">#</th>
                    <th class="text-center" style="width: 50%">Akses</th>
                    <th class="text-center" style="width: 20%">Default</th>
                    <th class="text-center" style="width: 20%">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($dataAkses as $key => $existakses)
                    <tr>
                        <td class="text-center">{{ ($key+1) }}</td>
                        <td>{{ $existakses->akses->nama }}</td>
                        <td class="text-center">
                            @if($existakses->is_default == 1)
                                <span class="text-success">Ya</span>
                            @else
                                <span class="text-danger">Tidak</span>
                            @endif
                        </td>
                        <td class="text-center" nowrap>
                            @if($existakses->is_default != 1)
                                <a href="javascript:;" class="btn btn-sm btn-success" onclick="jadikanDefault('{{ $existakses->id_akses }}', '{{ $idUser }}')"><span class="bx bx-check"></span>&nbsp;Jadikan Default</a>
                            @endif
                            <a href="javascript:;" class="btn btn-sm btn-danger" onclick="hapusAkses('{{ $existakses->id_akses }}', '{{ $idUser }}')"><span class="bx bx-trash"></span></a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="frm-default" action="{{ route('manajemen-user.setdefaultrole') }}" method="POST">
    @csrf
    <input type="hidden" id="id_akses_default" name="id_akses" required>
    <input type="hidden" id="id_user_default" name="id_user" required>
</form>

<form id="frm-hapus" action="{{ route('manajemen-user.hapusakses') }}" method="POST">
    @csrf
    <input type="hidden" id="id_akses_hapus" name="id_akses" required>
    <input type="hidden" id="id_user_hapus" name="id_user" required>
</form>
