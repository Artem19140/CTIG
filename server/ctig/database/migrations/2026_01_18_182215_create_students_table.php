<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('surname', 40);
            $table->string('name', 40);
            $table->string('patronymic', 40)->nullable();
            $table->date('date_birth');
            $table->string('surname_latin', 40);
            $table->string('name_latin', 40);
            $table->string('patronymic_latin', 40)->nullable();
            $table->string('passport_number', 15);
            $table->string('passport_series', 10);
            $table->unique(['passport_series', 'passport_number']);
            $table->string('issued_by', 50);
            $table->date('issues_date');
            $table->string('address_reg', 50);
            $table->string('migration_card_requisite', 40);
            $table->char('citizenship', 2);
            $table->string('phone', 12);

            $table->string('photo_path')->nullable()->default(null);
            $table->string('passport_scan_path')->nullable()->default(null);
        
            $table->char('exam_code', 6)->nullable()->unique()->default(null);
            $table->dateTime('exam_code_expired_at')->nullable()->default(null);
            $table->integer('exam_id')->nullable()->default(null);

            $table->foreignId('creator_id')
                ->constrained('users');

            $table->dateTime('storage_expired_at')->default(now()->addYears(3));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
