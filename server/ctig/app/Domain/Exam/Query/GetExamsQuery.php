<?php 

namespace App\Domain\Exam\Query;

use App\Models\Exam;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class GetExamsQuery{
    public function execute(array $data): LengthAwarePaginator
    {
        $examTypeId = $data['examTypeId'] ?? false;
        $dateFrom = $data['dateFrom'] ?? false;
        $dateTo = $data['dateTo'] ?? false;
        $addressId = $data['addressId'] ?? false;
        $cancelled = $data['cancelled'] ?? false;
        $completed = $data['completed'] ?? false;
        $perPage = $data['perPage'] ?? 10;

        $query = Exam::with(['examType'])
            ->withCount('foreignNationals');

        $query->when($examTypeId, fn ($q) =>
            $q->where('exam_type_id', $examTypeId)
        );

        $query->when($dateFrom, fn ($q) =>
            $q->where('begin_time', '>=', Carbon::parse($dateFrom)->startOfDay())
        );

        $query->when($dateTo, fn ($q) =>
            $q->where('begin_time', '<=', Carbon::parse($dateTo)->endOfDay())
        );

        $query->when($addressId, fn ($q) =>
            $q->where('address_id', $addressId)
        );

        $query->when($completed, fn ($q) =>
            $q->where('end_time', '<=', Carbon::now())
        );

        $query->when($cancelled, fn ($q) =>
            $q->where('is_cancelled', $cancelled)
        );

        $now = Carbon::now();

        $query->orderByRaw("
                CASE WHEN begin_time_utc >= ? THEN 0 ELSE 1 END
            ", [$now])
            ->orderByRaw("
                CASE WHEN begin_time_utc >= ? THEN begin_time END ASC
            ", [$now])
            ->orderByRaw("
                CASE WHEN begin_time_utc < ? THEN begin_time END DESC
            ", [$now]);

        return $query->paginate($perPage);
    }
}