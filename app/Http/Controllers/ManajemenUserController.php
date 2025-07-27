<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ManajemenUserRepository;
use App\Http\Services\ManajemenUserServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Nonstandard\Uuid;
use Yajra\DataTables\DataTables;

class ManajemenUserController extends Controller
{
    private $service;
    public function __construct()
    {
        $this->service = new ManajemenUserServices(new ManajemenUserRepository());
    }
    public function index()
    {
        $title = "Manajemen User";

        return view('pages.manajemenuser.index', compact('title'));
    }

    public function getData(Request $request){
        if ($request->ajax()) {
            $data_user = $this->service->getDataUser();

            return DataTables::of($data_user)
                ->addIndexColumn()
                ->addColumn('nama', function ($dataUser) {
                    return '<b>'.$dataUser->name.'</b><br><i class="small text-muted">'.$dataUser->kartu_id.'</i>';
                })
                ->addColumn('email', function ($dataUser) {
                    $htmlStatus = $dataUser->email_verified_at? '<span class="badge bg-sm text-success">Terverifikasi</span>':'<span class="badge bg-sm text-warning">Belum Terverifikasi</span>';
                    return '<span class="text-muted">'.$dataUser->email.'</span><br>'.$htmlStatus;
                })
                ->addColumn('nohp', function ($dataUser) {
                    return '<span class="small">'.$dataUser->no_hp.'</span>';
                })
                ->addColumn('created', function ($dataUser) {
                    return '<span class="small">'.$dataUser->created_at->format('d M Y H:i').'</span>';
                })
                ->addColumn('aksi', function ($dataUser) {
                    if ($dataUser->email_verified_at) {
                        $html = '<a href="javascript:void(0)" class="btn btn-sm py-1 px-2 btn-primary"><span class="bx bx-edit-alt"></span><span class="d-none d-lg-inline-block">&nbsp;Akses</span></a>&nbsp;';
                    }else{
                        $html = '<span class="badge bg-sm text-warning">Belum Terverifikasi</span>';
                    }
                    return $html;
                })
                ->rawColumns(['nama', 'aksi', 'email', 'nohp', 'created']) // Untuk render tombol HTML
                ->filterColumn('nama', function($query, $keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%")->orWhere('kartu_id', 'LIKE', "%{$keyword}%");
                })
                ->filterColumn('email', function($query, $keyword) {
                    $query->where('email', 'LIKE', "%{$keyword}%");
                })
                ->filterColumn('nohp', function($query, $keyword) {
                    $query->where('no_hp', 'LIKE', "%{$keyword}%");
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
