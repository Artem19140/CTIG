<?php

namespace App\Http\Controllers\Document;

use App\Models\Document;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        
    }

    public function store(Request $request)
    {
        if (! $request->hasFile('file')) {
            abort(400, 'Файл не передан');
        }
        $file = $request->file('file');
        $path = $file->store('uploads', 'public');

        return response()->json([
            'path' => $path,
            'url'  => Storage::disk('public')->url($path),
        ]);
    }

    public function show(Document $document)
    {
        //
    }

    public function update(Request $request, Document $document)
    {
        //
    }

    public function destroy(Document $document)
    {
        //
    }
}
