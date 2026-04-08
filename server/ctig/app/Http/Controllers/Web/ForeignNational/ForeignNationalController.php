<?php

namespace App\Http\Controllers\Web\ForeignNational;

use App\Domain\ForeignNational\Action\CreateForeignNationalStatementAction;
use App\Domain\ForeignNational\Action\CreateForeignNationalWithEnrollmentAction;
use App\Domain\ForeignNational\Action\UpdateForeignNationalAction;
use App\Domain\ForeignNational\Query\GetForeignNationalsQuery;
use App\Http\Requests\ForeignNational\ForeignNationalIndexRequest;
use App\Http\Requests\ForeignNational\ForeignNationalPostRequest;
use App\Http\Requests\ForeignNational\ForeignNationalUpdateRequest;
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

        $foreignNational = $createForeignNationalWithExamEnrollment
                ->execute(
                            $request->validated(),
                            $request->validated('examId'),
                            $request->user()
                        );
        Inertia::flash([
            'success' => 'ИГ создан',
            'foreignNationalId' => $foreignNational->id,
            'redirectUrl' => route('foreign-nationals.application-forms', [
                'foreignNational' =>$foreignNational->id,
                'examId' => $request->validated('examId'),
            ])
        ]);
        return back();
    }

    public function getApplicationForm (
                                        Request $request, 
                                        ForeignNational $foreignNational, 
                                        CreateForeignNationalStatementAction $createForeignNationalStatement
                                    ){
        $request->validate(['examId' => ['required', 'integer', 'exists:exams,id']]);
        $applicationFormPdf = $createForeignNationalStatement->execute($request->input('examId'), $foreignNational, $request->user());
        return $applicationFormPdf->stream('exam.pdf');
    }

    public function show(Request $request, ForeignNational $foreignNational){
        $foreignNational->load(['exams' => ['attempts' => function ($query) use($foreignNational): void{
            $query->where('foreign_national_id', $foreignNational->id);
            },
            'examType']
        ]);
        return new ForeignNationalResource($foreignNational);
    }

    public function update(
                            ForeignNationalUpdateRequest $request, 
                            ForeignNational $foreignNational,
                            UpdateForeignNationalAction $updateForeignNational
                        )
    {   
        $updateForeignNational->execute($request->validated(), $foreignNational);
        return Inertia::flash('success', 'Данные обновлены')->back();
    }

    public function destroy(ForeignNational $foreignNational)
    {
        $foreignNational->delete();
        //return $this->noContent();
    }

}
