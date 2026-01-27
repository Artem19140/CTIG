<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('exam_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students');
            $table->foreignId('exam_id')
                ->constrained('exams');
            
            $table->boolean('is_banned'); //Можно тоже статус сделать
            $table->boolean('is_rated')->default(false);
            $table->dateTime('expired_at');
            $table->boolean('is_finished')->default(true); //можно и без этого
            $table->dateTime('finished_at');//мб и started_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_attempts');
    }
};
