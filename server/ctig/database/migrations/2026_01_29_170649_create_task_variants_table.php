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
            $table->jsonb('content');
            $table->string('fipi_number')->unique();
            $table->foreignId('task_id')
                ->constrained('tasks')
                ->cascadeOnDelete();
            $table->boolean('is_active')->default(true);

            $table->string('group_number')->nullable()->default(null);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_variants');
    }
};
