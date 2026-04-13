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
        
        Schema::create('centers', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('short_name');
            $table->string('director_fio');
            $table->string('certificates_issue_address');
            $table->boolean('is_active')->default(true);
            $table->string('ogrn');
            $table->string('inn');
            $table->string('address');
            $table->string('name_genitive');
            $table->string('time_zone');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('centers');
    }
};
