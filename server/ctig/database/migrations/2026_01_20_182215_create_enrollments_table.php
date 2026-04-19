<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            $table->boolean('has_payment')->default(false);

            $table->unsignedInteger('reg_number');

            $table->dateTime('cancelled_at')->nullable()->default(null);

            $table->foreignId('cancelled_by_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->index()
                ->constrained()
                ->cascadeOnDelete();

            $table->string('exam_code')
                ->index()
                ->nullable()
                ->unique()
                ->default(null);

            $table->string('exam_code_used_at')
                ->nullable()
                ->default(null);

            $table->dateTime('exam_code_expired_at')
                ->index()
                ->nullable()
                ->default(null);

            $table->foreignId('foreign_national_id')
                ->constrained()
                ->cascadeOnDelete();
            
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('center_id')
                ->constrained('centers')
                ->cascadeOnDelete();

            $table->unique(['foreign_national_id', 'exam_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
