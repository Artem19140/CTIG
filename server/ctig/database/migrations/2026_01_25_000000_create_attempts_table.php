<?php

use App\Enums\AttemptStatusEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students');
            $table->foreignId('exam_id')
                ->constrained('exams');
            
            
            $table->string('status')->default(AttemptStatusEnum::Started);
            $table->dateTime('expired_at');
            $table->unsignedTinyInteger('total_mark')->default(0);
            $table->boolean('is_passed')->nullable()->default(null);
            $table->dateTime('last_activity_at')->nullable()->default(null);
            $table->dateTime('started_at');
            $table->dateTime('finished_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
