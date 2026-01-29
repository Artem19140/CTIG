<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->dateTime('begin_time');
            $table->dateTime('end_time')->nullable();
            $table->boolean('is_finished')->default(false);
            $table->boolean('is_codes_generate')->default(false);
            $table->string('time_zone')->default('Europe/Samara'); //с клиента брать 
            $table->string('status')->default('Ожидается');
            $table->unsignedTinyInteger('capacity');
            $table->date('exam_date');
            $table->foreignId('exam_type_id') 
                ->constrained('exam_types')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->foreignId('exam_address_id')
                ->constrained('exam_addresses')
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

    public function down(): void
    {
        Schema::dropIfExists('exam_student');
        Schema::dropIfExists('exam_tester');
        Schema::dropIfExists('exams'); 
    }
};
