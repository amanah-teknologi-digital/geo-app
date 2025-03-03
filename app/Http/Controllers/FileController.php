<?php

namespace App\Http\Controllers;

use App\Models\Files;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileController extends Controller
{
    public function getPrivateFile($id_file){
        $data_file = Files::find($id_file);

        if (Storage::disk('local')->exists($data_file->location)) {
            $file = Storage::disk('local')->get($data_file->location);
            return response($file, 200)->header('Content-Type', $data_file->mime);
        }else{
            abort(404, 'File Tidak Ditemukan');
        }
    }
}
