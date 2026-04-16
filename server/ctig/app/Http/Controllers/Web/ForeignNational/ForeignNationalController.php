<?php

namespace App\Http\Controllers\Web\ForeignNational;

use App\Domain\ForeignNational\Action\CreateForeignNationalWithEnrollmentAction;
use App\Domain\ForeignNational\Action\UpdateForeignNationalAction;
use App\Domain\ForeignNational\Query\ExportForeignNationalQuery;
use App\Domain\ForeignNational\Query\GetForeignNationalsQuery;
use App\Exceptions\BusinessException;
use App\Http\Requests\ForeignNational\ForeignNationalExportRequest;
use App\Http\Requests\ForeignNational\ForeignNationalIndexRequest;
use App\Http\Requests\ForeignNational\ForeignNationalPostRequest;
use App\Http\Requests\ForeignNational\ForeignNationalUpdateRequest;
use App\Http\Resources\ForeignNational\ForeignNationalProfileResource;
use App\Models\ForeignNational;
use App\Http\Resources\ForeignNational\ForeignNationalResource;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Inertia;

class ForeignNationalController 
{

    public function index(ForeignNationalIndexRequest $request, GetForeignNationalsQuery $getForeignNationalsQuery){
        $foreignNationals = $getForeignNationalsQuery->execute($request->validated() ?? []);
        Inertia::flash(['filters' => request()->all()]);
        return Inertia::render('ForeignNationals/ForeignNationals', [
            'foreignNationals' => ForeignNationalResource::collection($foreignNationals)
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
        $foreignNational->load( [
            'enrollments' => [ 'exam.examType', 'attempt', 'foreignNational'],
        ]);
        $foreignNational->enrollments = $foreignNational->enrollments->sortByDesc('exam.begin_time_utc');
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

    public function exportAvailable(
        ForeignNationalExportRequest $request,
    ){
        $this->ensureExportAvailable($request->validated('dateFrom'), $request->validated('dateTo'), $request->validated('citizenship'));
        return response()->json([
            'redirectUrl' => route('foreign-nationals.export', [
                'dateFrom' => $request->validated('dateFrom'),
                'dateTo' => $request->validated('dateTo'),
                'citizenship' => $request->validated('citizenship'),
            ])
        ]);
    }

    public function export(
                            ForeignNationalExportRequest $request,
                            ExportForeignNationalQuery $exportForeignNationalQuery
                        ){
        //Токо директор
        $this->ensureExportAvailable($request->validated('dateFrom'), $request->validated('dateTo'), $request->validated('citizenship'));
        return response()->streamDownload(function () use ($exportForeignNationalQuery, $request) {
            $exportForeignNationalQuery->execute($request->validated());
        }, 'Выгрузка_ИГ.csv');
    }

    protected function ensureExportAvailable(string $dateFrom, string $dateTo, string | null $citizenship = null){
        $available = ForeignNational::whereBetween('created_at', [
                Carbon::parse($dateFrom), 
                Carbon::parse($dateTo)
            ])
            ->when($citizenship, function (Builder $query) use($citizenship){
                $query->where('citizenship', $citizenship);
            })
            ->exists();
        if(!$available){
            throw new BusinessException('Нет данных ИГ для выгрузки');
        }
    }

}
