<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')
                ->constrained('exams');
            $table->foreignId('block_id')
                ->constrained('blocks');
            $table->foreignId('task_variant_id')
                ->constrained('task_variants');
            $table->foreignId('attempt_id')
                ->constrained('attempts')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->integer('checked_by_id')->nullable()->default(null);
            $table->unsignedTinyInteger('mark')->default(0);
            $table->string('student_answer', 2000)->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
