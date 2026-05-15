<?php

namespace App\Http\Controllers\Web\ForeignNational;

use App\Domain\ForeignNational\Action\CreateForeignNationalWithEnrollmentAction;
use App\Domain\ForeignNational\Action\UpdateForeignNationalAction;
use App\Domain\ForeignNational\Query\GetForeignNationalsQuery;
use App\Enums\EmployeeRole;
use App\Http\Requests\ForeignNational\ForeignNationalIndexRequest;
use App\Http\Requests\ForeignNational\ForeignNationalPostRequest;
use App\Http\Requests\ForeignNational\ForeignNationalUpdateRequest;
use App\Http\Resources\ForeignNational\ForeignNationalIndexResource;
use App\Http\Resources\ForeignNational\ForeignNationalProfileResource;
use App\Models\Enrollment;
use App\Models\ForeignNational;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ForeignNationalController 
{
    public function index(
        ForeignNationalIndexRequest $request, 
        GetForeignNationalsQuery $getForeignNationalsQuery
    ){
        Gate::authorize('viewAny', ForeignNational::class);

        $foreignNationals = $getForeignNationalsQuery->execute($request->validated() ?? []);

        Inertia::flash(['filters' => request()->all()]);

        $employee = $request->user();

        return Inertia::render('ForeignNationals/ForeignNationals', [
            'foreignNationals' => ForeignNationalIndexResource::collection($foreignNationals),
            'permissions' => [
                'create' => $employee->can('create', ForeignNational::class),
                'export' => $employee->can('export', ForeignNational::class),
                'statistics' => $employee->hasAnyRole(EmployeeRole::Director),
                'ministryEducation' => $employee->hasAnyRole(EmployeeRole::Director),
            ]
        ]);
    }

    public function store(
        ForeignNationalPostRequest $request, 
        CreateForeignNationalWithEnrollmentAction $createForeignNationalWithEnrollmentAction
    ){
        Gate::authorize('create', ForeignNational::class);
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
    public function show(
        Request $request,
        ForeignNational $foreignNational
    ){
        Gate::authorize('view', $foreignNational);
        $foreignNational->load([
            'creator',
            'enrollments' => [ 
                'exam' => ['type', 'center'], 
                'attempt.center'
            ]
        ]);
        $foreignNational->enrollments = $foreignNational->enrollments->sortByDesc('exam.begin_time');
        $employee = $request->user();
        return response()->json([
            'foreignNational' => new ForeignNationalProfileResource($foreignNational),
            'permissions' => [
                'enroll' => $employee->can('create', Enrollment::class),
                'edit' => $employee->can('update', $foreignNational),
                'files'  => $employee->hasAnyRole(EmployeeRole::Operator)
            ]
        ]);
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