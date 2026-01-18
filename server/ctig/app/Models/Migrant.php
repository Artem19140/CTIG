<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Migrant extends Model{
    protected $fillable=[
        'surname',
        'name',
        'patronymic',
        'date_birth',
        'surname_latin',
        'name_latin',
        'patronymic_latin',
        'passport_number',
        'passport_series',
        'issued_by',
        'issues_date',
        'address_reg',
        'migration_card_requisite',
        'citizenship',
        'phone'
    ];
}



