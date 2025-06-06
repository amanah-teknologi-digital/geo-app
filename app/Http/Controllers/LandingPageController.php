<?php

namespace App\Http\Controllers;

use App\Models\Pengaturan;
use App\Models\Pengumuman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class LandingPageController extends Controller
{
    public function index(){
        $pengaturan = Pengaturan::with(['files_geoletter', 'files_georoom', 'files_geofacility'])->first();
        $pengumumanterbaru = Pengumuman::with(['user','file_pengumuman','postinger_user'])
            ->where('is_posting', 1)->orderBy('created_at', 'desc')
            ->take(3)->get();

        return view('landing_page.index', compact('pengaturan','pengumumanterbaru'));
    }

    public function lihatPengumuman($id_pengumuman){
        $pengaturan = Pengaturan::with(['files_geoletter', 'files_georoom', 'files_geofacility'])->first();

        $data = Pengumuman::with(['user','file_pengumuman','postinger_user'])
            ->where('is_posting', 1)->where("id_pengumuman", $id_pengumuman)->first();

        return view('landing_page.lihat_pengumuman', compact('data','pengaturan'));
    }

    public function listPengumuman(){
        $pengaturan = Pengaturan::with(['files_geoletter', 'files_georoom', 'files_geofacility'])->first();

        return view('landing_page.list_pengumuman', compact('pengaturan'));
    }

    public function getListPengumuman(Request $request){
        if ($request->ajax()) {
            $data_pengumuman = Pengumuman::with(['user','file_pengumuman','postinger_user'])
                ->where('is_posting', 1)->orderBy('created_at', 'desc');

            return DataTables::of($data_pengumuman)
                ->addIndexColumn()
                ->addColumn('judul', function ($data_pengumuman) {
                    //return $data_pengumuman->judul;
                    $file = $data_pengumuman->gambar_header;
                    $filePath = $data_pengumuman->file_pengumuman->location;
                    $imageUrl = Storage::disk('public')->exists($filePath)
                        ? route('file.getpublicfile', $file)
                        : asset('assets/img/no_image.jpg');

                    return '<div class="d-flex align-items-center">
                            <img src="' . $imageUrl . '" alt="Gambar" class="rounded me-2" style="width: 50px; height: 50px; object-fit: cover;">
                            <span>' . $data_pengumuman->judul . '</span>
                        </div>';
                })
                ->addColumn('pembuat', function ($data_pengumuman) {
                    return '<span class="text-muted" style="font-size: smaller;font-style: italic">'.$data_pengumuman->user->name.
                        ',<br> pada '.$data_pengumuman->created_at->format('d-m-Y H:i').'</span>';
                })
                ->addColumn('posting', function ($data_pengumuman) {
                    return $data_pengumuman->is_posting? '<span class="badge bg-sm text-success">Posting</span>':'<span class="badge bg-sm text-warning">Tidak</span>';
                })
                ->addColumn('aksi', function ($data_pengumuman) {
                    $html = '<a href="'.route('pengumuman.lihatpengumuman', $data_pengumuman->id_pengumuman).'" class="btn btn-sm btn-primary"><span class="bi bi-eye"></span>&nbsp;Lihat</a>&nbsp;';
                    return $html;
                })
                ->rawColumns(['aksi', 'posting', 'pembuat','judul']) // Untuk render tombol HTML
                ->filterColumn('judul', function($query, $keyword) {
                    $query->where('judul', 'LIKE', "%{$keyword}%");
                })
                ->filterColumn('created_at', function($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(created_at, '%d-%m-%Y %H:%i') LIKE ?", ["%{$keyword}%"]);
                })
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }
}
