<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengaturanRepository;
use App\Http\Services\PengaturanServices;
use App\Models\Pengaturan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;

class PengaturanController extends Controller
{
    private $service;
    private $title;
    public function __construct()
    {
        $this->service = new PengaturanServices(new PengaturanRepository());
        $this->title = 'Pengaturan';
    }
    public function index()
    {
        $title = $this->title;
        $dataPengaturan = $this->service->getDataPengaturan();

        return view('pages.pengaturan.index', compact('dataPengaturan','title'));
    }

    public function updatePengaturan(Request $request){
        try {
            $dataPengaturan = Pengaturan::first();

            $request->validate([
                'alamat' => ['required'],
                'admin_geoletter' => ['required'],
                'admin_ruang' => ['required'],
                'admin_peralatan' => ['required'],
                'link_yt' => ['required'],
                'link_fb' => ['required'],
                'link_ig' => ['required'],
                'link_linkedin' => ['required'],
                'file_sop_geoletter' => ['file', 'mimes:jpeg,png,jpg,pdf', 'max:10240']
            ],[
                'alamat.required' => 'Alamat wajib diisi.',
                'admin_geoletter.required' => 'Admin Geoletter wajib diisi.',
                'admin_ruang.required' => 'Admin Ruang wajib diisi.',
                'admin_peralatan.required' => 'Admin Peralatan wajib diisi.',
                'link_yt.required' => 'Link YouTube wajib diisi.',
                'link_fb.required' => 'Link Facebook wajib diisi.',
                'link_ig.required' => 'Link Instagram wajib diisi.',
                'link_linkedin.required' => 'Link LinkedIn wajib diisi.',
                'file.file' => 'File yang diunggah tidak valid.',
                'file.mimes' => 'File harus berupa gambar (JPEG, PNG, JPG) atau PDF.',
                'file.max' => 'Ukuran file tidak boleh lebih dari 10 MB.',
            ]);

            DB::beginTransaction();

            //save file geo letter
            if ($request->hasFile('file_sop_geoletter')) {
                if (empty($dataPengaturan->file_sop_geoletter)){
                    $id_file_geoletter = strtoupper(Uuid::uuid4()->toString());
                }else{
                    $id_file_geoletter = $dataPengaturan->file_sop_geoletter;
                }

                $this->service->createOrUpdateFile($request->file('file_sop_geoletter'), $id_file_geoletter,'file_sop_geoletter');
            }

            //save file geo room
            if ($request->hasFile('file_sop_georoom')) {
                if (empty($dataPengaturan->file_sop_georoom)){
                    $id_file_georoom = strtoupper(Uuid::uuid4()->toString());
                }else{
                    $id_file_georoom = $dataPengaturan->file_sop_georoom;
                }

                $this->service->createOrUpdateFile($request->file('file_sop_georoom'), $id_file_georoom,'file_sop_georoom');
            }

            //save file geo facility
            if ($request->hasFile('file_sop_geofacility')) {
                if (empty($dataPengaturan->file_sop_geofacility)){
                    $id_file_geofacility = strtoupper(Uuid::uuid4()->toString());
                }else{
                    $id_file_geofacility = $dataPengaturan->file_sop_geofacility;
                }

                $this->service->createOrUpdateFile($request->file('file_sop_geofacility'), $id_file_geofacility,'file_sop_geofacility');
            }

            $this->service->updatePengaturan($request);

            DB::commit();

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (ValidationException $e) {
            DB::rollBack();
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
