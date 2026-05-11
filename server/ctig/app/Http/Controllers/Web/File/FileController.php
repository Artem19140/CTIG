<?php

namespace App\Http\Controllers\Web\File;

use App\Models\User;
use App\Support\Log\BusinessLog;
use Illuminate\Http\Request;
use Storage;

class FileController
{
    public function show(Request $request){
        $path=$request->input('path');

        if (!Storage::disk('local')->exists($path)) {

            abort(404);
        }

        BusinessLog::event('file_access', [
            'path'=>$path
        ]);
        return Storage::disk('local')->response($path);
    }
}
