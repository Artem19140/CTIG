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
        Schema::create('answers', function (Blueprint $table) {
            $table->id();
            $table->json('content');
            $table->boolean('is_correct')->default(false);
            $table->foreignId('task_variant_id')
                ->constrained('task_variants')
                ->cascadeOnDelete();     
            $table->unsignedTinyInteger('order');       
            $table->string('file_path')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('answers');
    }
};
