<?php

namespace App\Exceptions;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Contracts\Debug\ShouldntReport;

class BusinessException extends BaseException implements ShouldntReport
{
    public function render(Request $request){
        if($request->expectsJson()){
            return response()->json([
                'message' => $this->message
            ], 400);
        }

        if($request->inertia()){
            return Inertia::flash('error', $this->message)->back();
        }

        return back()->with('error', $this->message);
    }
}
