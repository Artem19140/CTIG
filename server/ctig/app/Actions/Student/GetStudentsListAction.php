<?php

namespace App\Actions\Student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;

class GetStudentsListAction{
    public function execute(array $data = []){
        $citizenship = $data['citizenship'] ?? false;
        return Student::with('creator')
            ->when($citizenship, function (Builder $query, string $citizenship) {
                $query->where('citizenship', $citizenship);
            })
            ->latest('created_at')
            ->simplePaginate();
    }
}