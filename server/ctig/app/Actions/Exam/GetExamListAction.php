<?php 

namespace App\Actions\Exam;

use App\Models\Exam;
use Illuminate\Database\Eloquent\Builder;

class GetExamListAction{
    public function execute(array $data){
        $examTypeId = $data['examTypeId'] ?? false;
        $dateFrom = $data['dateFrom'] ?? false;
        $dateTo = $data['dateTo'] ?? false;
        $addressId = $data['addressId'] ?? false;
        $exams = Exam::with(['examType'])
            ->when($examTypeId, function (Builder $query, int $examTypeId) {
                $query->where('exam_type_id', $examTypeId);
            })
            ->when($dateFrom, function (Builder $query, string $dateFrom){
                $query->where('date', '>=',$dateFrom);
            })
            ->when($dateTo, function (Builder $query, string $dateTo){
                $query->where('date', '<=',$dateTo);
            })
            ->when($addressId, function (Builder $query, string $addressId){
                $query->where('address_id',$addressId);
            })
            ->paginate(15);
        return $exams;
    }
}