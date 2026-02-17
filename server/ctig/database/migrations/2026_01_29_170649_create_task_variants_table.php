<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_variants', function (Blueprint $table) {
            $table->id();
            $table->string('content');
            $table->string('fipi_guid')->unique();
            $table->foreignId('task_id')->constrained('tasks');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_audio_task_listened')->default(false);
            $table->unsignedTinyInteger('group_id')->nullable()->default(null);
            $table->unsignedTinyInteger('mark');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_variants');
    }
};
