<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;

class Migrant extends Model{

    use HasFactory, Notifiable;
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
    
    public function exams(): BelongsToMany{
        return $this->belongsToMany(Exam::class);
    }

}



