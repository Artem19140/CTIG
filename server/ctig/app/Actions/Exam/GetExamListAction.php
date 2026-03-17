<?php 

namespace App\Actions\Exam;

use App\Models\Exam;
use DB;
use Illuminate\Database\Eloquent\Builder;

class GetExamListAction{
    public function execute(array $data){
        $examTypeId = $data['examTypeId'] ?? false;
        $dateFrom = $data['dateFrom'] ?? false;
        $dateTo = $data['dateTo'] ?? false;
        $addressId = $data['addressId'] ?? false;
        $perPage = $data['perPage'] ?? 15;
        return Exam::with(['examType', 'address', 'testers'])
            ->withCount('students')
            ->when($examTypeId, function (Builder $query, int $examTypeId) {
                $query->where('exam_type_id', $examTypeId);
            })
            ->when($dateFrom, function (Builder $query, string $dateFrom){
                $query->where('begin_time', '>=',$dateFrom);
            })
            ->when($dateTo, function (Builder $query, string $dateTo){
                $query->where('begin_time', '<=',$dateTo);
            })
            ->when($addressId, function (Builder $query, string $addressId){
                $query->where('address_id',$addressId);
            })
            //->where('is_cancelled', false)
            ->latest('begin_time')
            ->paginate($perPage);
    }
}