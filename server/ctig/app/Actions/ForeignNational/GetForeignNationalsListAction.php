<?php

namespace App\Actions\ForeignNational;

use App\Models\ForeignNational;
use Illuminate\Database\Eloquent\Builder;

class GetForeignNationalsListAction{
    public function execute(array $data = []){
        $surname = $data['surname'] ?? false;
        $name = $data['name'] ?? false;
        $patronymic = $data['patronymic'] ?? false;
        $passportSeries = $data['passportSeries'] ?? false;
        $passportNumber = $data['passportNumber'] ?? false;
        $id = $data['id'] ?? false;
        $perPage = $data['perPage'] ?? 10;
        return ForeignNational::when($surname, function (Builder $query, string $surname) {
                    $query->where('surname', 'like',trim($surname) ."%");
                })
                ->when($name, function (Builder $query, string $name) {
                    $query->where('name', 'like', trim($name) ."%");
                })
                ->when($patronymic, function (Builder $query, string $patronymic) {
                    $query->where('patronymic', 'like', trim($patronymic) ."%");
                })
                ->when($passportSeries, function (Builder $query, string $passportSeries) {
                    $query->where('passport_series', trim($passportSeries));
                })
                ->when($passportNumber, function (Builder $query, string $passportNumber) {
                    $query->where('passport_number', trim($passportNumber));
                })
                ->when($id, function (Builder $query, string $id) {
                    $query->where('id', trim($id));
                })
                ->latest('id')
                ->paginate($perPage); //->withQueryString()
    }
}