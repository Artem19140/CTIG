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
            $table->unsignedTinyInteger('level'); //У экзамена еще, оказывается, есть уровень
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->unsignedSmallInteger('duration');
            $table->string('certificate_name');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
     
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_types');
    }
};
