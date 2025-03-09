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
                ->addColumn('pembuat', function ($data_pengumuman) {
                    return '<span class="text-muted" style="font-size: smaller;font-style: italic">'.$data_pengumuman->user->name.
                        ', pada '.$data_pengumuman->created_at->format('d-m-Y H-i').'</span>';
                })
                ->addColumn('posting', function ($data_pengumuman) {
                    return $data_pengumuman->is_posting? '<span class="badge badge-xs bg-success">posting</span>':'<span class="badge badge-xs bg-warning">tidak</span>';
                })
                ->addColumn('aksi', function ($data_pengumuman) {
                    return '
                        <a href="'.route('pengumuman.edit', $data_pengumuman->id_pengumuman).'" class="btn btn-sm btn-primary"><span class="bx bx-edit-alt"></span>&nbsp;Edit</a>
                        <button class="btn btn-sm btn-danger delete-user" data-id="'.$data_pengumuman->id_pengumuman.'"><span class="bx bx-trash"></span>&nbsp;Hapus</button>
                    ';
                })
                ->rawColumns(['aksi', 'posting', 'pembuat']) // Untuk render tombol HTML
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
                'gambar_header' => ['required', 'file', 'images', 'max:5048']
            ],[
                'judul.required' => 'Judul wajib diisi.',
                'editor_quil.required' => 'Konten wajib diisi.',
                'gambar_header.required' => 'Gambar Header wajib diisi.',
                'gambar_header.file' => 'File yang diunggah tidak valid.',
                'gambar_header.images' => 'File harus berupa gambar (JPEG, PNG, JPG).',
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

    public function editPengumuman($id_pengumuman){
        $title = "Edit Pengumuman";
        $dataPengumuman = $this->service->getDataPengumuman($id_pengumuman);

        return view('pages.pengumuman.edit', compact('dataPengumuman','title'));
    }

    public function doeditPengumuman(Request $request){
        try {
            $request->validate([
                'id_pengumuman' => ['required'],
                'judul' => ['required'],
                'editor_quil' => ['required'],
                'gambar_header' => ['file', 'mimes:jpeg,png,jpg', 'max:5048']
            ],[
                'id_pengumuman.required' => 'Id Pengumuman tidak ada.',
                'judul.required' => 'Judul wajib diisi.',
                'editor_quil.required' => 'Konten wajib diisi.',
                'gambar_header.file' => 'File yang diunggah tidak valid.',
                'gambar_header.mimes' => 'File harus berupa gambar (JPEG, PNG, JPG).',
                'gambar_header.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]);

            $dataPengumuman = $this->service->getDataPengumuman($request->id_pengumuman);

            //save file gambar header
            $id_file_gambar = $dataPengumuman->gambar_header;
            if ($request->hasFile('gambar_header')) {
                $this->service->tambahFile($request->file('gambar_header'), $id_file_gambar);
            }

            $this->service->updatePengumuman($request);

            return redirect()->back()->with('success', 'Berhasil Update Pengumuman.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
