<?php

namespace App\Http\Controllers\Web\ForeignNational;

use App\Domain\ForeignNational\Action\CreateForeignNationalWithEnrollmentAction;
use App\Domain\ForeignNational\Action\UpdateForeignNationalAction;
use App\Domain\ForeignNational\Query\GetForeignNationalsQuery;
use App\Http\Requests\ForeignNational\ForeignNationalIndexRequest;
use App\Http\Requests\ForeignNational\ForeignNationalPostRequest;
use App\Http\Requests\ForeignNational\ForeignNationalUpdateRequest;
use App\Http\Resources\ForeignNational\ForeignNationalProfileResource;
use App\Models\ForeignNational;
use App\Http\Resources\ForeignNational\ForeignNationalResource;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ForeignNationalController 
{

    public function index(ForeignNationalIndexRequest $request, GetForeignNationalsQuery $getForeignNationalsQuery){
        $foreignNationals = $getForeignNationalsQuery->execute($request->validated() ?? []);
        return Inertia::render('ForeignNationals/ForeignNationals', [
            'foreignNationals' => ForeignNationalResource::collection($foreignNationals),
            'filters' => request()->all()
        ]);
    }

    public function store(
                            ForeignNationalPostRequest $request, 
                            CreateForeignNationalWithEnrollmentAction $createForeignNationalWithExamEnrollment
                        ){

        $enrollement = $createForeignNationalWithExamEnrollment
                ->execute(
                            $request->validated(),
                            $request->validated('examId'),
                            $request->user()
                        );
        return Inertia::flash([
            'redirectUrl' => route('enrollments.statements', ['enrollment' => $enrollement])
        ])->back();
    }
    public function show(ForeignNational $foreignNational){
        $foreignNational->load(['exams' => ['attempts' => function ($query) use($foreignNational): void{
            $query->where('foreign_national_id', $foreignNational->id);
            },
            'examType']
        ]);
        return new ForeignNationalProfileResource($foreignNational);
    }

    public function update(
                            ForeignNationalUpdateRequest $request, 
                            ForeignNational $foreignNational,
                            UpdateForeignNationalAction $updateForeignNational
                        )
    {   
        $updatedForeignNational = $updateForeignNational->execute($request->validated(), $foreignNational);
        return Inertia::flash([
            'foreignNational' => new ForeignNationalProfileResource($updatedForeignNational),
            'success' => 'Данные обновлены'
        ])->back();
    }

    public function destroy(ForeignNational $foreignNational)
    {
        $foreignNational->delete();
        //return $this->noContent();
    }

}
