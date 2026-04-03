<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('foreign_nationals', function (Blueprint $table) {
            $table->id()->index();
            $table->string('surname');
            $table->string('name');
            $table->string('patronymic')->nullable()->default(null);
            $table->date('date_birth');
            $table->string('surname_latin');
            $table->string('name_latin');
            $table->string('document_type')->default('Паспорт');
            $table->string('patronymic_latin')->nullable()->default(null);
            $table->string('passport_number')->nullable()->default(null);
            $table->string('passport_series')->nullable()->default(null);
            $table->unique(['passport_series', 'passport_number', 'citizenship']);
            $table->string('issued_by');
            $table->date('issued_date');
            $table->string('address_reg');
            $table->string('migration_card_requisite')->nullable()->default(null);
            $table->char('citizenship', 2);
            $table->string('phone', 12);
            $table->char('gender', 1);
            $table->string('comment')->nullable()->default(null);


            $table->string('photo_path')->nullable()->default(null);
            $table->string('passport_scan_path')->nullable()->default(null);
            $table->string('passport_translate_scan')->nullable()->default(null);
        
            $table->string('exam_code')->index()->nullable()->unique()->default(null);
            $table->dateTime('exam_code_expired_at')->nullable()->default(null);
            $table->foreignId('exam_id')
                ->nullable()
                ->default(null)
                ->constrained('exams')
                ->cascadeOnDelete();

            $table->foreignId('creator_id')
                ->constrained('users');


            $table->dateTime('storage_expired_at')->default(now()->addYears(3));
            
            $table->timestamps();
        });

        Schema::create('exam_foreign_national', function (Blueprint $table) {
            $table->id();

            $table->boolean('has_payment')->default(false);

            $table->unsignedInteger('reg_number');

            $table->boolean('is_cancelled')->default(false);

            $table->foreignId('cancelled_by_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->index()
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('foreign_national_id')
                 ->constrained()
                ->cascadeOnDelete();
            
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->unique(['foreign_national_id', 'exam_id']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exam_foreign_national');
        Schema::dropIfExists('foreign_nationals');
    }
};
