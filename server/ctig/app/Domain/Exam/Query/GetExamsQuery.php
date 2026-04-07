<?php 

namespace App\Domain\Exam\Query;

use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class GetExamQuery{
    public function execute(array $data): LengthAwarePaginator{
        $examTypeId = $data['examTypeId'] ?? false;
        $dateFrom = $data['dateFrom'] ?? false;
        $dateTo = $data['dateTo'] ?? false;
        $addressId = $data['addressId'] ?? false;
        $cancelled = $data['cancelled'] ?? false;
        $completed = $data['completed'] ?? false;
        $perPage = $data['perPage'] ?? 10;
        return Exam::with(['examType'])
            ->withCount('foreignNationals')
            ->when($examTypeId, function (Builder $query, int $examTypeId) {
                $query->where('exam_type_id', $examTypeId);
            })
            ->when($dateFrom, function (Builder $query, string $dateFrom){
                $begin= Carbon::parse($dateFrom)->startOfDay();
                $query->where('begin_time', '>=',$begin);
            })
            ->when($dateTo, function (Builder $query, string $dateTo){
                $end= Carbon::parse($dateTo)->endOfDay();
                $query->where('begin_time', '<=',$end);
            })
            ->when($addressId, function (Builder $query, string $addressId){
                $query->where('address_id',$addressId);
            })
            ->when($completed, function (Builder $query){
                $query->where('end_time', '<=', Carbon::now());
            })
            ->where('is_cancelled', $cancelled)
            ->latest('begin_time')
            ->paginate($perPage);
    }
}