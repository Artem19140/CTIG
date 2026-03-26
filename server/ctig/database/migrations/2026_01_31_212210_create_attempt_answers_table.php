<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('exam_id')
                ->index()
                ->constrained('exams')
                ->cascadeOnDelete();
            $table->foreignId('task_variant_id')
                ->index()
                ->constrained('task_variants')
                ->cascadeOnDelete();
            $table->foreignId('attempt_id')
                ->index()
                ->constrained('attempts')
                ->cascadeOnDelete();
            $table->foreignId('student_id')
                ->index()
                ->constrained('students')
                ->cascadeOnDelete();

            $table->foreignId('answer_id')
                ->index()
                ->nullable()
                ->default(null)
                ->constrained('answers')
                ->cascadeOnDelete();

            $table->foreignId('organization_id')
                ->index()
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->boolean('audio_is_played')->default(false);
                
            $table->integer('checked_by_id')->nullable()->default(null);
            $table->boolean('is_checked')->index()->default(false);
            $table->unsignedTinyInteger('mark')->nullable()->default(null);
            $table->jsonb('answer')->index()->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('student_answers');
    }
};
