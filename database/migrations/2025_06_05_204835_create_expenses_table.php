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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expense_category_id')->constrained()->onDelete('cascade');
            $table->string('description');
            $table->decimal('amount', 10, 2);
            $table->date('due_date');
            $table->date('payment_date')->nullable();
            $table->enum('status', ['aberto', 'pago', 'vencido', 'cancelado'])->default('aberto');
            $table->json('attachments')->nullable();

            // Campos para recorrência
            $table->boolean('is_recurring')->default(false);
            $table->enum('recurrence_type', ['semanal', 'mensal', 'anual'])->nullable();
            $table->date('recurrence_end_date')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
