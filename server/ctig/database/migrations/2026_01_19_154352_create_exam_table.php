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
            $table->dateTime('begin_time')->index();
            $table->dateTime('begin_time_utc')->index();
            $table->dateTime('end_time')->index();

            $table->dateTime('begin_time_real')->nullable()->default(null);
            $table->dateTime('end_time_real')->nullable()->default(null);

            $table->unsignedTinyInteger('capacity');

            $table->foreignId('exam_type_id') 
                ->constrained('exam_types')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            
            $table->foreignId('address_id')
                ->index()
                ->constrained('addresses')
                ->cascadeOnDelete();

            $table->foreignId('center_id')
                ->constrained('centers')
                ->cascadeOnDelete();
            
            $table->boolean('is_cancelled')->default(false);
            $table->string('cancelled_reason')->nullable()->default(null);

            $table->tinyInteger('group')->nullable()->default(null);
            $table->mediumInteger('session')->nullable()->default(null);

            $table->string('comment')->nullable()->default(null);
            $table->string('protocol_comment', 1000)->nullable()->default(null);

            $table->index(['begin_time', 'end_time'], 'exams_begin_end_idx');

            $table->timestamps();
        });

        

         Schema::create('exam_examiner', function (Blueprint $table) {
            $table->id();

            $table->foreignId('exam_id')
              ->constrained('exams')
              ->cascadeOnDelete();

            $table->foreignId('examiner_id')
                ->index()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('center_id')
                ->constrained('centers')
                ->cascadeOnDelete();
            $table->unique(['examiner_id', 'exam_id']);

            $table->index(['exam_id', 'examiner_id']);
            $table->index(['examiner_id', 'exam_id']);

            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_examiner');
        Schema::dropIfExists('exams'); 
    }
};
