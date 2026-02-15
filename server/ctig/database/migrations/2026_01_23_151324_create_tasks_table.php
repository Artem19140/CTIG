<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('subblock_id')
                ->constrained('subblocks')
                ->cascadeOnDelete();
            $table->string('type');
            $table->boolean('is_active')->default(true);
            $table->unsignedTinyInteger('order');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
