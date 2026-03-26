<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id()->index();
            $table->string('surname');
            $table->string('name');
            $table->string('patronymic')->nullable();
            $table->date('date_birth');
            $table->string('surname_latin');
            $table->string('name_latin');
            $table->string('document_type')->default('Паспорт');
            $table->string('patronymic_latin')->nullable();
            $table->string('passport_number');
            $table->string('passport_series');
            $table->unique(['passport_series', 'passport_number', 'citizenship']);
            $table->string('issued_by');
            $table->date('issued_date');
            $table->string('address_reg');
            $table->string('migration_card_requisite')->nullable()->default(null);
            $table->char('citizenship', 2);
            $table->string('phone', 12);
            $table->char('gender', 1);

            $table->string('photo_path')->nullable()->default(null);
            $table->string('passport_scan_path')->nullable()->default(null);
            $table->string('passport_translate_scan')->nullable()->default(null);
        
            $table->string('exam_code')->index()->nullable()->unique()->default(null);
            $table->dateTime('exam_code_expired_at')->nullable()->default(null);
            $table->integer('exam_id')->nullable()->default(null);

            $table->foreignId('creator_id')
                ->constrained('users');

            // $table->foreignId('organization_id')
            //     ->constrained('organizations')
            //     ->cascadeOnDelete();

            $table->dateTime('storage_expired_at')->default(now()->addYears(3));
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
