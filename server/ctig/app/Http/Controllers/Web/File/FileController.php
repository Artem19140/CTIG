<?php

namespace App\Http\Controllers\Web\File;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Storage;

class FileController
{
    public function show(Request $request){
        $request->validate([
            'path' => ['required', 'string', 'max:255'],
        ]);
        $path=$request->input('path');

        if (!Storage::disk('local')->exists($path)) {

            abort(404);
        }
        Log::info('file_access', ['path'=>$path]);
        return Storage::disk('local')->response($path);
    }
}
