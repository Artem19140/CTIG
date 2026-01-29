<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exam_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedSmallInteger('code')->unique(); 
            $table->foreignId('exam_id')
                ->constrained('exams');
            $table->foreignId('student_id')
                ->constrained('students'); 
            $table->boolean('is_used')->default(false);
            $table->dateTime('expired_at');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_codes');
    }
};
