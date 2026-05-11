<?php

use App\Models\Center;
use App\Models\User;
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
        Schema::create('activity_table', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, 'actor_id')->nullable()->default(null);
            $table->string('actor_type')->nullable()->default(null);
            $table->string('event');
            $table->string('resource')->nullable()->default(null); 
            $table->jsonb('context');
            $table->jsonb('meta');
            $table->foreignIdFor(Center::class, 'center_id')->nullable()->default(null);
            $table->timestamps();

            $table->index(['event', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('metrics');
    }
};
