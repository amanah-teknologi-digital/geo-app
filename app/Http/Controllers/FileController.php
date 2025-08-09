<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function getPrivateFile($id_file){
        $data_file = Files::find($id_file);

        if (Storage::disk('private')->exists($data_file->location)) {
            $file = Storage::disk('private')->get($data_file->location);
            $decrypted = Crypt::decrypt($file);
            return response($decrypted, 200)
                ->header('Content-Type', $data_file->mime)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }else{
            abort(404, 'File Tidak Ditemukan');
        }
    }

    public function getPublicFile($id_file){
        $data_file = Files::find($id_file);

        if (Storage::disk('public')->exists($data_file->location)) {
            $file = Storage::disk('public')->get($data_file->location);
            $decrypted = Crypt::decrypt($file);
            return response($decrypted, 200)
                ->header('Content-Type', $data_file->mime)
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }else{
            abort(404, 'File Tidak Ditemukan');
        }
    }
}
