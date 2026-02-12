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
            $table->foreignId('exam_block_id')
                ->constrained('exam_types')
                ->cascadeOnDelete();
            $table->boolean('need_human_check');
            $table->boolean('is_active')->default(true);
            $table->string('associatied_with_fipi_guid')->nullable()->default(null);;
            $table->boolean('is_associated')->nullable()->default(false);
            $table->unsignedTinyInteger('order');
            //$table->foreignId('type_id'); что за тип
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
