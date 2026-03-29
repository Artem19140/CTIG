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

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();
            
            $table->boolean('is_cancelled')->default(false);
            $table->string('cancelled_reason')->nullable()->default(null);
            $table->tinyInteger('group')->nullable()->default(null);
            $table->mediumInteger('session')->nullable()->default(null);
            $table->string('comment')->nullable()->default(null);
            $table->string('examiner_comment', 1000)->nullable()->default(null);
            $table->timestamps();
        });

        Schema::create('exam_foreign_national', function (Blueprint $table) {
            $table->id();

            $table->boolean('has_payment')->default(false);

            $table->unsignedInteger('reg_number');

            $table->boolean('is_cancelled')->default(false);

            $table->foreignId('cancelled_by_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->index()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('foreign_national_id')
                 ->constrained()
                ->cascadeOnDelete();
            
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();
            $table->unique(['foreign_national_id', 'exam_id']);

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

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->timestamps();
        });        
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_foreign_national');
        Schema::dropIfExists('exam_examiner');
        Schema::dropIfExists('exams'); 
    }
};
