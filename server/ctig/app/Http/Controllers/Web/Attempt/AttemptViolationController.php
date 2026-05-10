<?php

namespace App\Http\Controllers\Web\Attempt;

use App\Http\Resources\Violation\ViolationResource;
use App\Models\Attempt;
use App\Models\Violation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class AttemptViolationController{
    public function index(Attempt $attempt){
        $this->authorize($attempt);
        $attempt->load('violations');
        return ViolationResource::collection($attempt->violations);
    }

    public function store(Request $request, Attempt $attempt){
        $this->authorize($attempt);
        $request->validate(['comment' => ['required', 'string']]);

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
        $this->authorize($attempt);
        abort_if($attempt->id !== $violation->attempt_id, 403);
        $request->validate(['comment' => ['required', 'string']]);
        $violation->comment =  $request->input('comment');
        $violation->save();
        return new ViolationResource($violation);
    }   

    public function destroy(
        Attempt $attempt, 
        Violation $violation
    ){
        $this->authorize($attempt);
        abort_if($attempt->id !== $violation->attempt_id, 403);
        $attempt->violations()->where('id', $violation->id)->delete();
        return response()->noContent();
    }
    protected function authorize(Attempt $attempt){
        Gate::authorize('attempt-examiner-access', $attempt);
    }
}
