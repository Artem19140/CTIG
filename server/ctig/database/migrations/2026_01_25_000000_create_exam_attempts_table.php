<?php

use App\Enums\ExamAttemptStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students');
            $table->foreignId('exam_id')
                ->constrained('exams');
            $table->boolean('is_audio_task_listened')->default(false);
            
            $table->string('status')->default(ExamAttemptStatus::Started);
            $table->dateTime('expired_at');
            $table->unsignedTinyInteger('total_mark');
            $table->boolean('is_passed')->default(null);
            $table->dateTime('last_activity_at')->nullable()->default(null);
            $table->dateTime('finished_at')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
