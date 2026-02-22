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
                ->constrained('exams')
                ->cascadeOnDelete();
            $table->foreignId('task_variant_id')
                ->constrained('task_variants')
                ->cascadeOnDelete();
            $table->foreignId('attempt_id')
                ->constrained('attempts')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->constrained('students')
                ->cascadeOnDelete();
            $table->integer('checked_by_id')->nullable()->default(null);
            $table->boolean('is_checked')->default(false);
            $table->unsignedTinyInteger('mark')->nullable()->default(null);
            $table->string('text_answer')->nullable()->default(null);
            $table->json('json_answer')->nullable()->default(null);
            $table->foreignId('answer_id')
                ->nullable()
                ->default(null)
                ->constrained('answers');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
