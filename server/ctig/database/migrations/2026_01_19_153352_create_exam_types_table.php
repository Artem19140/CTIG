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
            $table->unsignedTinyInteger('level'); 
            $table->unsignedSmallInteger('duration');
            $table->string('certificate_name');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('cost');
            $table->unsignedTinyInteger('tasks_count');
            $table->unsignedTinyInteger('min_mark');
            $table->boolean('need_human_check');
            $table->boolean('has_speaking_tasks');
            $table->timestamps();
        });
     
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_types');
    }
};
