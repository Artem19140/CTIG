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
            $table->foreignId('exam_block_id')
                ->constrained('exam_blocks');
            $table->foreignId('task_id')
                ->constrained('tasks');
            $table->boolean('is_right');
            $table->string('student_answer')->default(''); //или null??
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
