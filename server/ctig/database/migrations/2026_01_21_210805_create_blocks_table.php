<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blocks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->unsignedSmallInteger('min_mark');
            $table->foreignId('exam_type_id')
                ->constrained('exam_types')
                ->cascadeOnDelete();
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('order');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blocks');
    }
};

// $exam = ExamType::firstOrCreate(
//         ['level' => 1],
//             [
//             'name' => "Разрешение на работу (патент)",
//             'short_name' => 'ПАТЕНТ',
//             'duration' => 80,
//             'certificate_name'=>'Сертификат о владении русским языком, знании истории России и основ законодательства Российской Федерации на уровне, соответствующем цели получения разрешения на работу или патента'
//         ]);