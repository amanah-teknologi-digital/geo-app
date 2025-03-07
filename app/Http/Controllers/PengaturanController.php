<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengaturanRepository;
use App\Http\Services\PengaturanServices;
use App\Models\Pengaturan;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

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

            //save file data ke database
//            if ($request->hasFile('file_sop_geoletter')) {
//                $id_file_geoletter =
//            }

            $this->service->updatePengaturan($request);

            return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
