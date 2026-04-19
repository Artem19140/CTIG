<?php 

namespace App\Domain\Exam\Query;

use App\Models\Exam;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\Paginator;

class GetExamsQuery{
    public function execute(array $data, User $user):Paginator
    {
        $examTypeId = $data['examTypeId'] ?? false;
        $dateFrom = $data['dateFrom'] ?? false;
        $dateTo = $data['dateTo'] ?? false;
        $addressId = $data['addressId'] ?? false;
        $cancelled = $data['cancelled'] ?? false;
        $finished = $data['finished'] ?? false;
        $perPage = $data['perPage'] ?? 10;

        $query = Exam::with(['type'])
            ->withCount(['enrollments']);

        $query->when($examTypeId, fn ($q) =>
            $q->where('exam_type_id', $examTypeId)
        );

        $query->when($dateFrom, fn ($q) =>
            $q->whereBeginTimeMore(Carbon::parse($dateFrom)->startOfDay())
        );

        $query->when($dateTo, fn ($q) =>
            $q->whereBeginTimeLess(Carbon::parse($dateTo)->endOfDay())
        );

        $query->when($addressId, fn ($q) =>
            $q->where('address_id', $addressId)
        );

        $query->when($finished, fn ($q) =>
            $q->where('end_time', '<=', Carbon::now($user->time_zone))//timeZone
        );

        $cancelled ? $query->Cancelled() : $query->notCancelled();

        $now = Carbon::now($user->time_zone);
        $query->sorting($now);

        return $query->simplePaginate($perPage)
            ->withQueryString();
    }
}