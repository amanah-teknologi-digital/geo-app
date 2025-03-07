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
                'link_linkedin' => ['required']
            ],[
                'alamat.required' => 'Alamat wajib diisi.',
                'admin_geoletter.required' => 'Admin Geoletter wajib diisi.',
                'admin_ruang.required' => 'Admin Ruang wajib diisi.',
                'admin_peralatan.required' => 'Admin Peralatan wajib diisi.',
                'link_yt.required' => 'Link YouTube wajib diisi.',
                'link_fb.required' => 'Link Facebook wajib diisi.',
                'link_ig.required' => 'Link Instagram wajib diisi.',
                'link_linkedin.required' => 'Link LinkedIn wajib diisi.'
            ]);

            //save file data ke database
            $pengaturan = Pengaturan::first(); // Ambil data pertama di tabel Pengaturan

            if ($pengaturan) {
                $pengaturan->alamat = $request->alamat;
                $pengaturan->admin_geoletter = $request->admin_geoletter;
                $pengaturan->admin_ruang = $request->admin_ruang;
                $pengaturan->admin_peralatan = $request->admin_peralatan;
                $pengaturan->link_yt = $request->link_yt;
                $pengaturan->link_fb = $request->link_fb;
                $pengaturan->link_ig = $request->link_ig;
                $pengaturan->link_linkedin = $request->link_linkedin;
                $pengaturan->updater = auth()->user()->id;
                $pengaturan->created_at = now();
                $pengaturan->save(); // Simpan perubahan
            } else {
                Pengaturan::create([
                    'alamat' => $request->alamat,
                    'admin_geoletter' => $request->admin_geoletter,
                    'admin_ruang' => $request->admin_ruang,
                    'admin_peralatan' => $request->admin_peralatan,
                    'link_yt' => $request->link_yt,
                    'link_fb' => $request->link_fb,
                    'link_ig' => $request->link_ig,
                    'link_linkedin' => $request->link_linkedin,
                    'updater' => auth()->user()->id,
                    'updated_at' => now()
                ]);
            }

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
