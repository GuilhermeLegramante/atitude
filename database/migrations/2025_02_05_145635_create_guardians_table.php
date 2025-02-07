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
        Schema::create('guardians', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('relationship'); // Relationship to the student (e.g., Parent, Guardian)
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
        Schema::dropIfExists('guardians');
    }
};
