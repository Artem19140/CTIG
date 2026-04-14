<?php 

namespace App\Domain\Exam\Query;

use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class GetExamsQuery{
    public function execute(array $data, User $user): LengthAwarePaginator
    {
        $examTypeId = $data['examTypeId'] ?? false;
        $dateFrom = $data['dateFrom'] ?? false;
        $dateTo = $data['dateTo'] ?? false;
        $addressId = $data['addressId'] ?? false;
        $cancelled = $data['cancelled'] ?? false;
        $completed = $data['completed'] ?? false;
        $perPage = $data['perPage'] ?? 10;

        $query = Exam::with(['examType'])
            ->withCount(['enrollments']);

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
            $q->where('end_time', '<=', Carbon::now($user->time_zone))//timeZone
        );

        $query->when($cancelled, fn ($q) =>
            $q->notCancelled()
        );

        $now = Carbon::now($user->time_zone);
        $query
            ->orderByRaw("
                CASE
                    WHEN begin_time <= ? AND end_time > ? THEN 0
                    WHEN begin_time > ? THEN 1
                    ELSE 2
                END
            ", [$now, $now, $now])

            ->orderByRaw("
                CASE
                    WHEN begin_time <= ? AND end_time > ? THEN begin_time
                    WHEN begin_time > ? THEN begin_time
                END ASC
            ", [$now, $now, $now])

            ->orderByRaw("
                CASE
                    WHEN end_time <= ? THEN begin_time
                END DESC
            ", [$now]);

        return $query->paginate($perPage);
    }
}