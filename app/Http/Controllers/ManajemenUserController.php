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
                        $html = '<a href="javascript:;" data-id="'.$dataUser->id.'" data-bs-toggle="modal" data-bs-target="#modal-akses" class="btn btn-sm py-1 px-2 btn-primary"><span class="bx bx-plus"></span><span class="d-none d-lg-inline-block">&nbsp;Akses</span></a>&nbsp;';
                    }else{
                        $html = '<span class="badge bg-sm text-warning">Belum Terverifikasi</span>';
                    }
                    return $html;
                })
                ->rawColumns(['nama', 'aksi', 'email', 'nohp', 'created']) // Untuk render tombol HTML
                ->filterColumn('nama', function($query, $keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                })
                ->toJson();
        }

        return response()->json(['message' => 'Invalid request'], 400);
    }

    public function getDataUser(Request $request){
        $idUser = $request->id_user;
        $dataUser = $this->service->getDataUser($idUser);
        $dataAkses = $dataUser->aksesuser;
        $listAkses = $this->service->getListAkses($dataAkses->pluck('id_akses'));

        $viewAksesUser = view('pages.manajemenuser.renderakses', compact('dataAkses', 'dataUser', 'listAkses' ,'idUser'))->render();
        $data = [
            'htmlForm' => $viewAksesUser,
        ];

        return response()->json($data);
    }

    public function updateAksesUser(Request $request){
        try {
            $request->validate([
                'id_user' => ['required'],
                'id_akses' => ['required'],
            ],[
                'id_user.required' => 'IdUser tidak ada.',
            ]);

            $idUser = $request->id_user;
            $idAkses = $request->id_akses;

            $this->service->tambahAksesUser($idAkses, $idUser);

            return redirect()->back()->with('success', 'Berhasil Tambah Akses.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function setDefaultAkses(Request $request){
        try {
            $request->validate([
                'id_user' => ['required'],
                'id_akses' => ['required'],
            ],[
                'id_user.required' => 'IdUser tidak ada.',
            ]);

            $idUser = $request->id_user;
            $idAkses = $request->id_akses;

            $this->service->setDefaultAkses($idAkses, $idUser);

            return redirect()->back()->with('success', 'Berhasil Set Default Akses.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function hapusAksesUser(Request $request)
    {
        try {
            $request->validate([
                'id_user' => ['required'],
                'id_akses' => ['required'],
            ], [
                'id_user.required' => 'IdUser tidak ada.',
            ]);

            $idUser = $request->id_user;
            $idAkses = $request->id_akses;

            $dataUser = $this->service->getDataUser($idUser);
            $dataAkses = $dataUser->aksesuser;
            $listAkses = $dataAkses->pluck('id_akses');

            if (count($listAkses) == 1){
                return redirect()->back()->with('error', 'Akses user tidak boleh kosong.');
            }

            $this->service->hapusAkses($idAkses, $idUser);

            return redirect()->back()->with('success', 'Berhasil Hapus Akses.');
        } catch (ValidationException $e) {
            $errors = $e->errors();
            return redirect()->back()->withErrors($errors);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
