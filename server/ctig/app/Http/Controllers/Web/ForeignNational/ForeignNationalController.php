<?php

namespace App\Http\Controllers\Web\ForeignNational;

use App\Domain\ForeignNational\Action\CreateForeignNationalWithEnrollmentAction;
use App\Domain\ForeignNational\Action\UpdateForeignNationalAction;
use App\Domain\ForeignNational\Query\GetForeignNationalsQuery;
use App\Enums\Event;
use App\Enums\Resource;
use App\Http\Requests\ForeignNational\ForeignNationalIndexRequest;
use App\Http\Requests\ForeignNational\ForeignNationalPostRequest;
use App\Http\Requests\ForeignNational\ForeignNationalUpdateRequest;
use App\Http\Resources\ForeignNational\ForeignNationalIndexResource;
use App\Http\Resources\ForeignNational\ForeignNationalProfileResource;
use App\Models\ForeignNational;
use App\Support\Log\LogActivity;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ForeignNationalController 
{
    public function index(ForeignNationalIndexRequest $request, GetForeignNationalsQuery $getForeignNationalsQuery){
        $foreignNationals = $getForeignNationalsQuery->execute($request->validated() ?? []);
        Inertia::flash(['filters' => request()->all()]);
        return Inertia::render('ForeignNationals/ForeignNationals', [
            'foreignNationals' => ForeignNationalIndexResource::collection($foreignNationals)
        ]);
    }

    public function store(
        ForeignNationalPostRequest $request, 
        CreateForeignNationalWithEnrollmentAction $createForeignNationalWithEnrollmentAction
    ){
        $enrollement = $createForeignNationalWithEnrollmentAction
            ->execute(
                $request->validated(),
                $request->validated('examId'),
                $request->user()
            );
        return response()->json([
            'redirectUrl' => route('enrollments.statements', ['enrollment' => $enrollement])
        ]);
    }
    public function show(ForeignNational $foreignNational){
        Gate::authorize('view', $foreignNational);
        $foreignNational->load([
            'creator',
            'enrollments' => [ 
                'exam' => ['type', 'center'], 
                'attempt.center'
            ]
        ]);
        $foreignNational->enrollments = $foreignNational->enrollments->sortByDesc('exam.begin_time');

        LogActivity::event(
            event:Event::Access,
            resource:Resource::ForeignNational, 
            context:[
                'foreign_national_id' => $foreignNational->id
            ]
        );
        return new ForeignNationalProfileResource($foreignNational);
    }

    public function update(
        ForeignNationalUpdateRequest $request, 
        ForeignNational $foreignNational,
        UpdateForeignNationalAction $updateForeignNationalAction
    ){   
        $updatedForeignNational = $updateForeignNationalAction->execute(
            $request->validated(), 
            $foreignNational
        );
        return response()->json([
            'foreignNational' => new ForeignNationalProfileResource($updatedForeignNational)
        ]);
    }
}