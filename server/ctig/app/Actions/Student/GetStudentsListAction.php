<?php

namespace App\Actions\Student;

use App\Models\Student;
use Illuminate\Database\Eloquent\Builder;

class GetStudentsListAction{
    public function execute(array $data = []){
        $search = $data['search'] ?? false;
        return Student::when($search, function (Builder $query, string $search) {
                    $query->where('name', 'like',"$search%")
                        ->orWhere('surname', 'like',"$search%")
                        ->orWhere('patronymic', 'like',"$search%")
                        ->orWhere('passport_series', 'like',"$search%")
                        ->orWhere('passport_number', 'like',"$search%");
            })
            ->latest('created_at')
            ->simplePaginate();
    }
}