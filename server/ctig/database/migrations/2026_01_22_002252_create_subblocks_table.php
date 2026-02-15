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
        Schema::create('subblocks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedTinyInteger('order');
            $table->unsignedTinyInteger('min_mark');
            $table->foreignId('exam_block_id')
                ->constrained('exam_blocks');
            $table->foreignId('creator_id')
                ->constrained('users');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subblocks');
    }
};
