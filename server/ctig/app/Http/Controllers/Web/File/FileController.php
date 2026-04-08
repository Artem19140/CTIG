<?php

namespace App\Http\Controllers\Web\File;

use Illuminate\Http\Request;
use Storage;

class FileController
{
    public function show(Request $request){
        $path=$request->input('path');
        if (!Storage::disk('local')->exists($path)) {
            abort(404);
        }
        return Storage::disk('local')->response($path);
        }
}
