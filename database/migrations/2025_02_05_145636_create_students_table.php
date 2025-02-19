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
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');  
            $table->foreignId('guardian_id')->nullable()->constrained('guardians'); // Caso seja menor de idade
            $table->string('name');
            $table->string('photo')->nullable();
            $table->date('birth_date')->nullable();
            $table->string('rg')->nullable(); // RG
            $table->string('cpf')->nullable(); // CPF
            $table->string('gender')->nullable();
            $table->foreignId('address_id')->nullable()->constrained('addresses');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
