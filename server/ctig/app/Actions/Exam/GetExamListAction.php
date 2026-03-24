<?php 

namespace App\Actions\Exam;

use App\Models\Exam;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Builder;

class GetExamListAction{
    public function execute(array $data){
        $examTypeId = $data['examTypeId'] ?? false;
        $dateFrom = $data['dateFrom'] ?? false;
        $dateTo = $data['dateTo'] ?? false;
        $addressId = $data['addressId'] ?? false;
        $cancelled = $data['cancelled'] ?? false;
        $completed = $data['completed'] ?? false;
        $perPage = $data['perPage'] ?? 10;
        return Exam::with(['examType', 'address', 'examiners'])
            ->withCount('students')
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
            ->when($cancelled, function (Builder $query) use($cancelled){
                $query->where('is_cancelled', $cancelled);
            })
            ->latest('begin_time')
            ->paginate($perPage);
    }
}