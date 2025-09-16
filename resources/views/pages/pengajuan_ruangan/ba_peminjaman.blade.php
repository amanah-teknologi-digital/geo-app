<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: "Times New Roman", serif;
            font-size: 14px;
            line-height: 1.7;
            margin: 40px;
        }
        .kop {
            border-bottom: 3px double #000;
            padding-bottom: 10px;
            margin-bottom: 25px;
            position: relative;
            min-height: 90px;
        }
        .kop img {
            position: absolute;
            left: 0;
            top: 0;
            width: 100px;
            height: auto;
        }
        .kop .text {
            margin-left: 120px;
            text-align: center;
        }
        .kop h2, .kop h3, .kop p {
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse; /* hilangkan double border */
            margin-top: 12px;
            font-size: 13px;
        }
        table th, table td {
            border: 0.2px solid #555; /* lebih tipis dan warna soft */
            padding: 6px;
        }
        table thead th,
        table tr th:first-child{
            /*background-color: rgba(168, 255, 168, 0.5) !important; !* biru soft, aman diprint *!*/
            color: #000; /* tetap terbaca */
            text-align: left;
        }
        table tbody td:nth-child(1),
        table tbody td:nth-child(3),
        table tbody td:nth-child(4) {
            text-align: center;
        }
        .signature {
            width: 100%;
            margin-top: 50px;
        }
        .signature td {
            text-align: center;
            vertical-align: top;
            border: none;
            font-size: 14px;
        }

        .kop {
            display: flex;
            align-items: center; /* sejajarkan vertikal tengah */
            gap: 10px; /* jarak antara logo dan teks */
        }

        .kop img {
            width: 100px; /* atur sesuai kebutuhan */
            height: auto;
        }

        .kop .text {
            line-height: 1.2;
        }
    </style>
</head>
<body>
{{-- KOP SURAT --}}
<div class="kop">
    <img src="{{ public_path('landing_page_rss/teknikgeo.png') }}" alt="Logo">
    <div class="text">
        <h3 style="color: #0dcaf0; line-height: 1.2;">KEMENTERIAN PENDIDIKAN TINGGI, SAINS, DAN TEKNOLOGI</h3>
        <h3 style="color: rgb(8, 60, 132); line-height: 1.2;">INSTITUT TEKNOLOGI SEPULUH NOPEMBER <br>{{ $fakultas }}</h3>
        <p style="line-height: 1.2;">Kampus ITS Sukolilo, Surabaya 60111</p>
        <p style="line-height: 1.2;">Telp. (031) 5953476, Email: tgeofisika@its.ac.id</p>
    </div>
</div>

{{-- JUDUL --}}
<div style="text-align: center; margin-bottom: 20px;line-height: 1.2;">
    <h3 style="line-height: 1.2;"><u>BERITA ACARA PEMINJAMAN RUANGAN</u></h3>
    <p style="line-height: 1.2;">Nomor: P/{{ $dataPengajuan->urutan_surat }}/{{ $dataPengajuan->nomor_surat }}/{{ $dataPengajuan->created_at->format('Y') }}</p>
</div>

{{-- ISI --}}
<p>Pada hari ini, telah dilakukan pemeriksaan ruangan dengan rincian berikut:</p>

<table>
    <tr>
        <th style="width:25%">Nama Kegiatan</th>
        <td>{{ $dataPengajuan->nama_kegiatan }}</td>
    </tr>
    <tr>
        <th>Deskripsi</th>
        <td>{{ $dataPengajuan->deskripsi }}</td>
    </tr>
    <tr>
        <th>Tanggal</th>
        <td>{{ $dataPengajuan->tgl_mulai->format('d-m-Y') }} s/d {{ $dataPengajuan->tgl_selesai->format('d-m-Y') }} Jam {{ $dataPengajuan->jam_mulai->format('H:i') }} – {{ $dataPengajuan->jam_selesai->format('H:i') }}</td>
    </tr>
    <tr>
        <th>Pengaju</th>
        <td>{{ $dataPengajuan->nama_pengaju }}</td>
    </tr>
    <tr>
        <th>No Handphone</th>
        <td>{{ $dataPengajuan->no_hp }}</td>
    </tr>
    <tr>
        <th>Ruangan Dipinjam</th>
        <td>
            <ul style="margin:0; padding-left:15px;">
                @foreach($dataPengajuan->pengajuanruangandetail as $ruang)
                    <li>{{ $ruang->ruangan->kode_ruangan.' - '.$ruang->ruangan->nama }}</li>
                @endforeach
            </ul>
        </td>
    </tr>
    <tr>
        <th>Petugas Pemeriksa</th>
        <td>{{ $dataPengajuan->pemeriksaawal->name }}</td>
    </tr>
</table>

<h4 style="margin-top:20px;">Peralatan</h4>
<table>
    <thead>
    <tr>
        <th>No</th>
        <th>Nama Peralatan</th>
        <th>Jumlah</th>
        <th>Kondisi</th>
        <th>Keterangan</th>
    </tr>
    </thead>
    <tbody>
    @foreach($dataPengajuan->pengajuanperalatandetail as $i => $alat)
        <tr>
            <td>{{ $i+1 }}</td>
            <td>{{ $alat->nama_sarana }}</td>
            <td>{{ $alat->jumlah }}</td>
            <td>{{ $alat->is_valid_awal == 1 ? 'Ada' : 'Tidak' }}</td>
            <td>{{ $alat->keterangan_awal }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<p style="margin-top:20px;">Demikian berita acara ini dibuat untuk dipergunakan sebagaimana mestinya.</p>

{{-- TANDA TANGAN --}}
<table class="signature">
    <tr>
        <td>
            Mengetahui,<br>
            <br><br><br><br>
            <u>{{ $dataPengajuan->nama_penyetuju }}</u><br>
            {{ $dataPengajuan->pihak_penyetuju }}
        </td>
        <td>
            Surabaya, {{ now()->translatedFormat('d F Y') }}<br>
            <br><br><br><br>
            <u>{{ $dataPengajuan->nama_pengaju }}</u><br>
            Pengaju
        </td>
    </tr>
</table>

</body>
</html>
