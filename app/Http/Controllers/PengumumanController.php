<?php

namespace App\Http\Controllers;

use App\Http\Repositories\PengumumanRepository;
use App\Http\Services\PengumumanServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
        $title = "List Pengumuman";

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
                        ',<br> pada '.$data_pengumuman->created_at->format('d-m-Y H:i').'</span>';
                })
                ->addColumn('posting', function ($data_pengumuman) {
                    return $data_pengumuman->is_posting? '<span class="badge bg-sm text-success">Posting</span>':'<span class="badge bg-sm text-warning">Tidak</span>';
                })
                ->addColumn('aksi', function ($data_pengumuman) {
                    $html = '<a href="'.route('pengumuman.edit', $data_pengumuman->id_pengumuman).'" class="btn btn-sm py-1 px-2 btn-primary"><span class="bx bx-edit-alt"></span><span class="d-none d-lg-inline-block">&nbsp;Edit</span></a>&nbsp;';
                    $html .= '<div class="d-inline-block"><a href="javascript:;" class="btn btn-icon dropdown-toggle hide-arrow me-1" data-bs-toggle="dropdown" aria-expanded="false"><i class="bx bx-dots-vertical-rounded icon-base"></i></a>';
                    $html .= '<div class="dropdown-menu dropdown-menu-end m-0" style="">';
                    if ($data_pengumuman->is_posting == 1) {
                        $html .= '<a href="javascript:;" class="dropdown-item text-warning batal-posting" data-id="'.$data_pengumuman->id_pengumuman.'" data-bs-toggle="modal" data-bs-target="#modal-unpost"><span class="bx bx-candles"></span>&nbsp;Unposting</a>';
                    }else{
                        $html .= '<a href="javascript:;" class="dropdown-item text-success posting-pengumuman" data-id="'.$data_pengumuman->id_pengumuman.'" data-bs-toggle="modal" data-bs-target="#modal-post"><span class="bx bx-paper-plane"></span>&nbsp;Posting</a>';
                    }
                    $html .= '<div class="dropdown-divider"></div>';
                    $html .= '<a href="javascript:;" class="dropdown-item text-danger delete-record" data-id="'.$data_pengumuman->id_pengumuman.'" data-bs-toggle="modal" data-bs-target="#modal-hapus"><span class="bx bx-trash"></span>&nbsp;Hapus</a>';
                    $html .= '</div></div>';
                    return $html;
                })
                ->rawColumns(['aksi', 'posting', 'pembuat']) // Untuk render tombol HTML
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

    public function tambahPengumuman(){
        $title = "Tambah Pengumuman";

        return view('pages.pengumuman.tambah', compact('title'));
    }

    public function dotambahPengumuman(Request $request){
        try {
            $request->validate([
                'judul' => ['required'],
                'editor_pengumuman' => ['required', 'string', 'min:10'],
                'gambar_header' => ['required', 'file', 'image', 'max:5048']
            ],[
                'judul.required' => 'Judul wajib diisi.',
                'editor_pengumuman.required' => 'Konten wajib diisi.',
                'gambar_header.required' => 'Gambar Header wajib diisi.',
                'gambar_header.file' => 'File yang diunggah tidak valid.',
                'gambar_header.image' => 'File harus berupa gambar.',
                'gambar_header.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]);

            DB::beginTransaction();
            //save file gambar header
            $id_file_gambar = strtoupper(Uuid::uuid4()->toString());
            $this->service->tambahFile($request->file('gambar_header'), $id_file_gambar);
            $this->service->tambahPengumuman($request, $id_file_gambar);

            DB::commit();

            return redirect(route('pengumuman'))->with('success', 'Berhasil Tambah Pengumuman.');
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

    public function editPengumuman($id_pengumuman){
        $title = "Edit Pengumuman";
        $dataPengumuman = $this->service->getDataPengumuman($id_pengumuman);
        if ($dataPengumuman->is_posting){
            $is_edit = false;
        }else{
            $is_edit = true;
        }

        return view('pages.pengumuman.edit', compact('dataPengumuman', 'is_edit', 'title'));
    }

    public function doeditPengumuman(Request $request){
        try {
            $request->validate([
                'id_pengumuman' => ['required'],
                'judul' => ['required'],
                'editor_pengumuman' => ['required', 'string', 'min:10'],
                'gambar_header' => ['file', 'image', 'max:5048']
            ],[
                'id_pengumuman.required' => 'Id Pengumuman tidak ada.',
                'judul.required' => 'Judul wajib diisi.',
                'editor_pengumuman.required' => 'Konten wajib diisi.',
                'gambar_header.file' => 'File yang diunggah tidak valid.',
                'gambar_header.image' => 'File harus berupa gambar.',
                'gambar_header.max' => 'Ukuran file tidak boleh lebih dari 5 MB.',
            ]);

            $dataPengumuman = $this->service->getDataPengumuman($request->id_pengumuman);

            DB::beginTransaction();
            //save file gambar header
            $id_file_gambar = $dataPengumuman->gambar_header;
            if ($request->hasFile('gambar_header')) {
                $this->service->tambahFile($request->file('gambar_header'), $id_file_gambar);
            }

            $this->service->updatePengumuman($request);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Update Pengumuman.');
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

    public function hapusPengumuman(Request $request){
        try {
            $request->validate([
                'id_pengumuman' => ['required'],
            ],[
                'id_pengumuman.required' => 'Id Pengumuman tidak ada.',
            ]);

            $dataPengumuman = $this->service->getDataPengumuman($request->id_pengumuman);

            DB::beginTransaction();

            $this->service->hapusPengumuman($dataPengumuman->id_pengumuman);

            //hapus file gambar header
            $id_file_gambar = $dataPengumuman->gambar_header;
            $location = $dataPengumuman->file_pengumuman->location;
            $this->service->hapusFile($id_file_gambar, $location);

            DB::commit();

            return redirect()->back()->with('success', 'Berhasil Hapus Pengumuman.');
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
    public function postingPengumuman(Request $request){
        try {
            $request->validate([
                'id_pengumuman' => ['required'],
            ],[
                'id_pengumuman.required' => 'Id Pengumuman tidak ada.',
            ]);

            $id_pengumuman = $request->id_pengumuman;

            $this->service->postingPengumuman($id_pengumuman);

            return redirect()->back()->with('success', 'Berhasil Posting Pengumuman.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function batalPostingPengumuman(Request $request){
        try {
            $request->validate([
                'id_pengumuman' => ['required'],
            ],[
                'id_pengumuman.required' => 'Id Pengumuman tidak ada.',
            ]);

            $id_pengumuman = $request->id_pengumuman;

            $this->service->batalPostingPengumuman($id_pengumuman);

            return redirect()->back()->with('success', 'Berhasil Batal Posting Pengumuman.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
