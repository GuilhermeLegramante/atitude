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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->enum('status', ['aberto', 'pago', 'vencido', 'cancelado']);
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->string('payment_method')->nullable(); // pix, boleto, etc
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
