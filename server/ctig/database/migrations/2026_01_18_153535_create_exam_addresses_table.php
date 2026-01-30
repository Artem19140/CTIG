<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up(): void
    {
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->string('address');
            $table->boolean('is_actual');
            $table->foreignId('creator_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->timestamps();
        }); //Мб сделать полиморфную связь? Чтобы и адрес сертификата тоже туда же впихивать
    }
    
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
