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

            $table->string('name')->nullable()->default(null);
            $table->string('short_name');
            $table->string('director_fio')->nullable()->default(null);
            $table->string('certificates_issue_address')->nullable()->default(null);
            $table->boolean('is_active')->default(true);
            $table->string('ogrn')->nullable()->default(null);
            $table->string('inn')->nullable()->default(null);
            $table->string('address')->nullable()->default(null);
            $table->string('name_genitive')->nullable()->default(null);
            $table->string('time_zone')->nullable()->default(null);
            $table->string('commission_chairman')->nullable()->default(null);
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
