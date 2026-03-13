<?php

use App\Enums\AttemptStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')
                ->constrained('students') 
                ->cascadeOnDelete();
                
            $table->foreignId('ban_by_id')
                ->nullable()
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('exam_id')
                ->constrained('exams');

            $table->foreignId('organization_id')
                ->constrained('organizations')
                ->cascadeOnDelete();

            $table->string('status')->default(AttemptStatus::Pending);
            $table->dateTime('expired_at')->nullable()->default(null);
            $table->string('ban_reason')->nullable()->default(null);
            $table->unsignedTinyInteger('total_mark')->default(0);
            $table->boolean('is_passed')->nullable()->default(null);
            $table->dateTime('last_activity_at')->nullable()->default(null);
            $table->dateTime('started_at')->nullable()->default(null);
            $table->dateTime('finished_at')->nullable()->default(null);
            $table->timestamps();

            $table->unique(['exam_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attempts');
    }
};
