<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Http\Resources\Violation\ViolationResource;
use App\Models\Attempt;
use App\Models\Violation;
use Illuminate\Http\Request;

class AttemptViolationController
{
    public function index(Attempt $attempt){
        $attempt->load('violations');
        return ViolationResource::collection($attempt->violations);
    }

    public function store(Request $request, Attempt $attempt){
        $request->validate(['violation' => ['required', 'string']]);
        $violation = $attempt->violations()->create([
            'comment' => $request->input('comment')
        ]);
        return new ViolationResource($violation);
    }

    public function update(
        Request $request, 
        Attempt $attempt, 
        Violation $violation
    ){
        $request->validate(['comment' => ['required', 'string']]);
        $violation->comment =  $request->input('comment');
        $violation->save();
        return new ViolationResource($violation);
    }   

    public function destroy(
        Attempt $attempt, 
        Violation $violation
    ){
        $attempt->violations()->where('id', $violation->id)->delete();
        return response()->noContent();
    }
}
