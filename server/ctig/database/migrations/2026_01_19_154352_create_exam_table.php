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
            $table->string('time_zone')->default('Europe/Samara'); //с клиента брать 
            $table->unsignedTinyInteger('capacity');
            $table->date('date');
            $table->foreignId('exam_type_id') 
                ->constrained('exam_types')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->foreignId('address_id')
                ->constrained('addresses')
                ->cascadeOnDelete();
            
            $table->boolean('is_cancelled')->default(false);
            $table->tinyInteger('group')->nullable()->default(null);
            $table->mediumInteger('session')->nullable()->default(null);
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
            
            // $table->foreignId('creator_id')
            // ->constrained('users')
            // ->cascadeOnDelete();

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
