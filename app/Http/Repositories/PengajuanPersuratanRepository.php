<?php

namespace App\Http\Repositories;

use App\Models\FilePengajuanSurat;
use App\Models\Files;
use App\Models\JenisSurat;
use App\Models\PengajuanPersuratan;
use App\Models\Pengumuman;
use App\Models\PersetujuanPersuratan;
use App\Models\PihakPenyetujuPengajuanSurat;
use App\Models\PihakPenyetujuSurat;
use App\Models\User;
use Ramsey\Uuid\Nonstandard\Uuid;

class PengajuanPersuratanRepository
{
    public function getDataPengajuan($id_pengajuan, $id_akses){
        $data = PengajuanPersuratan::select('id_pengajuan', 'pengaju', 'id_statuspengajuan', 'id_jenissurat', 'nama_pengaju', 'no_hp', 'email', 'email_its', 'kartu_id', 'created_at', 'updated_at', 'updater', 'keterangan', 'data_form')
            ->with(['pihakpengaju','pihakupdater','jenis_surat','statuspengajuan','persetujuan','filesurat','pihakpenyetuju']);

        $id_pengguna = auth()->user()->id;
        if ($id_akses != 1) {
            // Semua selain superadmin: hanya yang berkaitan
            $data = $data->where(function ($q) use ($id_pengguna, $id_akses) {
                $q->where('pengaju', $id_pengguna)
                    ->orWhereHas('pihakpenyetuju', function ($q2) use ($id_pengguna) {
                        $q2->where('id_penyetuju', $id_pengguna);
                    });
            });
        }

        $data = $data->orderByRaw('CASE
            WHEN id_statuspengajuan = 0 THEN 0
            WHEN id_statuspengajuan = 4 THEN 1
            WHEN id_statuspengajuan = 5 THEN 2
            ELSE 3
            END')
            ->orderBy('created_at', 'desc');

        if (!empty($id_pengajuan)) {
            return $data->where('id_pengajuan', $id_pengajuan)->first();
        }

//        $data = $data->get();
//
//        $data = $data->filter(function ($pengajuan) use ($id_pengguna, $id_akses) {
//            // Superadmin: tidak difilter
//            if ($id_akses == 1) return true;
//
//            // Jika dia pengaju
//            if ($pengajuan->pengaju == $id_pengguna) return true;
//
//            // Cek apakah dia penyetuju
//            $penyetuju = $pengajuan->pihakpenyetuju->firstWhere('id_penyetuju', $id_pengguna);
//            if (!$penyetuju) return false;
//
//            $urutan = $penyetuju->urutan;
//
//            if ($urutan == 1) {
//                // Urutan pertama: hanya jika status pengajuan = diajukan (2)
//                return $pengajuan->id_statuspengajuan == 2;
//            }
//
//            // Urutan > 1: cek penyetuju sebelumnya sudah setujui
//            $prev = $pengajuan->pihakpenyetuju->where('urutan', $urutan - 1)->first();
//            if (!$prev) return false;
//
//            $prev_persetujuan = $pengajuan->persetujuan->firstWhere('id_pihakpenyetuju', $prev->id_pihakpenyetuju);
//            return $prev_persetujuan && $prev_persetujuan->id_statuspersetujuan == 1;
//        });

        return $data;
    }

    public function getJenisSurat($id_jenissurat, $isEdit){
        $data = JenisSurat::select('id_jenissurat', 'nama', 'default_form', 'created_at', 'updated_at')->with(['pihakpenyetujusurat', 'pihakpenyetujusurat.userpenyetuju'])->orderBy('created_at');
        if (!empty($id_jenissurat)) {
            $data = $data->where('id_jenissurat', $id_jenissurat)->first();
        }else{
            if ($isEdit){
                $data = $data->where('is_aktif', 1)->get();
            }else{
                $data = $data->get();
            }
        }

        return $data;
    }

    public function getDataFile($idFile){
        $data = Files::find($idFile);

        return $data;
    }

    public function tambahPengajuan($request, $id_pengajuan){
        PengajuanPersuratan::create([
            'id_pengajuan' => $id_pengajuan,
            'pengaju' => auth()->user()->id,
            'id_statuspengajuan' => 0, //draft
            'id_jenissurat' => $request->jenis_surat,
            'nama_pengaju' => auth()->user()->name,
            'no_hp' => auth()->user()->no_hp,
            'email' => auth()->user()->email,
            'email_its' => auth()->user()->email_its,
            'kartu_id' => auth()->user()->kartu_id,
            'keterangan' => $request->keterangan,
            'data_form' => $request->editor_surat,
            'created_at' => now(),
            'updater' => auth()->user()->id
        ]);

        $getPenyetujuSurat = PihakPenyetujuSurat::where('id_jenissurat', $request->jenis_surat)
            ->orderBy('urutan')
            ->get();

        foreach ($getPenyetujuSurat as $penyetujuSurat) {
            $id_persetujuan = strtoupper(Uuid::uuid4()->toString());

            PihakPenyetujuPengajuanSurat::create([
                'id_pihakpenyetuju' => $id_persetujuan,
                'id_pengajuan' => $id_pengajuan,
                'id_penyetuju' => $penyetujuSurat->id_penyetuju,
                'nama' => $penyetujuSurat->nama,
                'urutan' => $penyetujuSurat->urutan
            ]);
        }
    }

    public function updatePengajuan($request){
        $id_pengajuan = $request->id_pengajuan;
        $id_akses = session('akses_default_id');

        $dataPengajuan = $this->getDataPengajuan($id_pengajuan, $id_akses);
        $id_jenissurat = $dataPengajuan->id_jenissurat;
        $dataUser = User::find($dataPengajuan->pengaju);

        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->nama_pengaju = $dataUser->name;
        $dataPengajuan->kartu_id = $dataUser->kartu_id;
        $dataPengajuan->no_hp = $dataUser->no_hp;
        $dataPengajuan->email = $dataUser->email;
        $dataPengajuan->email_its = $dataUser->email_its;
        $dataPengajuan->id_jenissurat = $request->jenis_surat;
        $dataPengajuan->keterangan = $request->keterangan;
        $dataPengajuan->data_form = $request->editor_surat;
        $dataPengajuan->updated_at = now();
        $dataPengajuan->updater = auth()->user()->id;
        $dataPengajuan->save();

        if ($id_jenissurat != $request->jenis_surat) {
            // Jika jenis surat berubah, hapus pihak penyetuju yang ada
            PihakPenyetujuPengajuanSurat::where('id_pengajuan', $id_pengajuan)->delete();

            // Tambahkan pihak penyetuju baru sesuai jenis surat yang baru
            $getPenyetujuSurat = PihakPenyetujuSurat::where('id_jenissurat', $request->jenis_surat)
                ->orderBy('urutan')
                ->get();

            foreach ($getPenyetujuSurat as $penyetujuSurat) {
                $id_persetujuan = strtoupper(Uuid::uuid4()->toString());

                PihakPenyetujuPengajuanSurat::create([
                    'id_pihakpenyetuju' => $id_persetujuan,
                    'id_pengajuan' => $id_pengajuan,
                    'id_penyetuju' => $penyetujuSurat->id_penyetuju,
                    'nama' => $penyetujuSurat->nama,
                    'urutan' => $penyetujuSurat->urutan
                ]);
            }
        }
    }

    public function updateDataPemohon($id_pengajuan){
        $id_akses = session('akses_default_id');

        $dataPengajuan = $this->getDataPengajuan($id_pengajuan, $id_akses);
        $dataUser = User::find($dataPengajuan->pengaju);

        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->nama_pengaju = $dataUser->name;
        $dataPengajuan->kartu_id = $dataUser->kartu_id;
        $dataPengajuan->no_hp = $dataUser->no_hp;
        $dataPengajuan->email = $dataUser->email;
        $dataPengajuan->email_its = $dataUser->email_its;
        $dataPengajuan->save();
    }

    public function hapusPengajuan($id_pengajuan){
        //hapus pihak terkait
        PihakPenyetujuPengajuanSurat::where('id_pengajuan', $id_pengajuan)->delete();

        $pengajuan = PengajuanPersuratan::where('id_pengajuan', $id_pengajuan)->where('id_statuspengajuan', 0)->first();
        if ($pengajuan) {
            $pengajuan->delete();
        }
    }

    public function getPersetujuanTerakhir($id_pengajuan, $id_pihakpenyetuju){
        $data = PersetujuanPersuratan::with(['pihakpenyetuju','statuspersetujuan','akses'])
            ->where('id_pengajuan', $id_pengajuan)
            ->where('id_pihakpenyetuju', $id_pihakpenyetuju)
            ->orderBy('created_at', 'desc')
            ->first();

        return $data;
    }

    public function getPersetujuanTerakhirSuper($id_pengajuan){
        $data = PersetujuanPersuratan::with(['pihakpenyetuju','statuspersetujuan','akses'])
            ->where('id_pengajuan', $id_pengajuan)
            ->orderBy('created_at', 'desc')
            ->first();

        return $data;
    }

    public function ajukanPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 2;
        $dataPengajuan->save();
    }

    public function setujuiPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 1;
        $dataPengajuan->save();
    }

    public function revisiPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 4;
        $dataPengajuan->save();
    }

    public function sudahRevisiPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 5;
        $dataPengajuan->save();
    }

    public function tolakPengajuan($id_pengajuan){
        $dataPengajuan = PengajuanPersuratan::find($id_pengajuan);
        $dataPengajuan->id_statuspengajuan = 3;
        $dataPengajuan->save();
    }

    public function tambahPersetujuan($id_pengajuan, $id_akses, $id_statuspersetujuan, $keterangan){
        $id_persetujuan = strtoupper(Uuid::uuid4()->toString());

        PersetujuanPersuratan::create([
            'id_persetujuan' => $id_persetujuan,
            'id_pengajuan' => $id_pengajuan,
            'id_statuspersetujuan' => $id_statuspersetujuan,
            'id_akses' => $id_akses,
            'penyetuju' => auth()->user()->id,
            'nama_penyetuju' => auth()->user()->name,
            'keterangan' => $keterangan,
            'created_at' => now()
        ]);
    }

    public function tambahFile($id_file, $fileName, $filePath, $fileMime, $fileExt, $fileSize){
        $file = Files::find($id_file);

        if (!$file) {
            Files::create([
                'id_file' => $id_file,
                'file_name' => $fileName,
                'location' => $filePath,
                'mime' => $fileMime,
                'ext' => $fileExt,
                'file_size' => $fileSize,
                'created_at' => now(),
                'is_private' => 0,
                'updater' => auth()->user()->id
            ]);
        }
    }

    public function hapusFilePengajuan($idPengajuan, $idFile){
        $filePengajuanSurat = FilePengajuanSurat::where('id_pengajuan',$idPengajuan)->where('id_file', $idFile)->first();
        if ($filePengajuanSurat){
            $filePengajuanSurat->delete();
        }

        $files = Files::find($idFile);
        if ($files){
            $files->delete();
        }
    }

    public function tambahFileSurat($idPengajuan, $idFile){
        FilePengajuanSurat::create([
            'id_pengajuan' => $idPengajuan,
            'id_file' => $idFile
        ]);
    }
}
