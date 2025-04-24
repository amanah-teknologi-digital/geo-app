<?php

namespace App\Http\Controllers;

use App\Http\Repositories\ManajemenFileTinyMceRepository;
use App\Http\Services\ManajemenFileTinyMceServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

class ManajemenFileTinyMceController extends Controller{
    private $service;
    public function __construct()
    {
        $this->service = new ManajemenFileTinyMceServices(new ManajemenFileTinyMceRepository());
    }

    public function uploadGambarTinymce(Request $request){
        try {
            $request->validate([
                'id_user' => 'required',
                'file' => 'required|image|mimes:jpeg,png,jpg,webp|max:1024', // max 1MB
            ]);

            $idUser = $request->id_user;
            $image = $request->file('file');

            DB::beginTransaction();
            //save file gambar
            $idFileGambar = strtoupper(Uuid::uuid4()->toString());
            $filePath = $this->service->tambahFile($image, $idFileGambar, $idUser);

            DB::commit();

            return response()->json([
                'location' => asset('storage/'.$filePath)
            ]);
        } catch (ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Validation failed',
                'code' => Response::HTTP_UNPROCESSABLE_ENTITY, // 422
                'messages' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return response()->json([
                'error' => 'Upload error',
                'code' => $e->getCode() ?: 500,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
