<?php

namespace App\Http\Controllers\Web\File;

use App\Enums\Event;
use App\Enums\Resource;
use App\Support\Log\LogActivity;
use Illuminate\Http\Request;
use Storage;

class FileController
{
    public function show(Request $request){
        $path=$request->input('path');

        if (!Storage::disk('local')->exists($path)) {

            abort(404);
        }

        LogActivity::event(
            event: Event::Access, 
            resource: Resource::File,
            context:[
            'path'=>$path
        ]);
        return Storage::disk('local')->response($path);
    }
}
