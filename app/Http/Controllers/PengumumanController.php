<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengumumanRepository;
use App\Http\Services\PengumumanServices;
use App\Models\Pengumuman;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Nonstandard\Uuid;
use Yajra\DataTables\DataTables;

class PengumumanController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new PengumumanServices(new PengumumanRepository());
    }
    public function index()
    {
        $title = "Pengumuman";

        return view('pages.pengumuman.index', compact('title'));
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data_pengumuman = $this->service->getDataPengumuman();

            return DataTables::of($data_pengumuman)
                ->addIndexColumn()
                ->addColumn('judul', function ($data_pengumuman) {
                    return $data_pengumuman->judul;
                })
                ->addColumn('author', function ($data_pengumuman) {
                    return $data_pengumuman->user->name;
                })
                ->editColumn('tanggal_post', function ($data_pengumuman) {
                    return $data_pengumuman->created_at->format('d-m-Y');
                })
                ->addColumn('posting', function ($data_pengumuman) {
                    return $data_pengumuman->is_posting? '<span class="badge bg-success">posting</span>':'<span class="badge bg-danger">tidak</span>';
                })
                ->addColumn('aksi', function ($data_pengumuman) {
                    return '
                        <a href="'.url('users/edit/'.$data_pengumuman->id_pengumuman).'" class="btn btn-sm btn-primary">Edit</a>
                        <button class="btn btn-sm btn-danger delete-user" data-id="'.$data_pengumuman->id_pengumuman.'">Delete</button>
                    ';
                })
                ->rawColumns(['aksi', 'posting']) // Untuk render tombol HTML
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function tambahPengumuman(){
        $title = "Tambah Pengumuman";

        return view('pages.pengumuman.tambah', compact('title'));
    }

    public function dotambahPengumuman(Request $request){
        try {
            $request->validate([
                'judul' => ['required'],
                'editor_quil' => ['required'],
                'gambar_header' => ['required', 'file', 'mimes:jpeg,png,jpg', 'max:5048']
            ],[
                'judul.required' => 'Judul wajib diisi.',
                'editor_quil.required' => 'Konten wajib diisi.',
                'gambar_header.required' => 'Gambar Header wajib diisi.',
                'gambar_header.file' => 'File yang diunggah tidak valid.',
                'gambar_header.mimes' => 'File harus berupa gambar (JPEG, PNG, JPG).',
                'gambar_header.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]);

            //save file gambar header
            $id_file_gambar = strtoupper(Uuid::uuid4()->toString());
            $this->service->tambahFile($request->file('gambar_header'), $id_file_gambar);
            $this->service->tambahPengumuman($request, $id_file_gambar);

            return redirect(route('pengumuman'))->with('success', 'Berhasil Tambah Pengumuman.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
