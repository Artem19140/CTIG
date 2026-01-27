<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

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
            $table->dateTime('begin_time');
            $table->dateTime('end_time')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->boolean('is_codes_generate')->default(false);
            $table->string('time_zone')->default('Europe/Samara'); //с клиента брать 
            $table->string('status')->default('Ожидается');
            $table->tinyInteger('capacity');
            $table->foreignId('exam_type_id') 
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->foreignId('exam_adress_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->tinyInteger('group');
            $table->mediumInteger('session_number');
            $table->string('comment')->default('');
            $table->timestamps();
        });

        Schema::create('exam_student', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
              ->constrained()
              ->cascadeOnDelete();

            $table->foreignId('student_id')
              ->constrained()
              ->cascadeOnDelete();

            $table->timestamps();
        });

         Schema::create('exam_tester', function (Blueprint $table) {
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
        Schema::dropIfExists('exam_student');
        Schema::dropIfExists('exams');
        Schema::dropIfExists('exam_types');
    }
};
