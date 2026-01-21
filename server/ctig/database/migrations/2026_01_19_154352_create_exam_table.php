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
        Schema::create('exam_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('duration');
            $table->timestamps();
        });

        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->timestamp('exam_date');
            $table->boolean('is_conducted')->default(false);;

            $table->foreignId('exam_type_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->mediumInteger('session_number');
                
            $table->timestamps();
        });

        Schema::create('exam_migrant', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
              ->constrained()
              ->cascadeOnDelete();

            $table->foreignId('migrant_id')
              ->constrained()
              ->cascadeOnDelete();

            $table->timestamps();
        });

         Schema::create('tester_exam', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
              ->constrained('exams')
              ->cascadeOnDelete();

            $table->foreignId('tester_id')
              ->constrained('users')
              ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exam_migrant');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_types');
    }
};
