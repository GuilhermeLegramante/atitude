<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false);
            $table->string('recurrence_type')->nullable(); // 'mensal', 'anual', 'semanal'
            $table->date('recurrence_end_date')->nullable();
        });
    }

    public function down()
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['is_recurring', 'recurrence_type', 'recurrence_end_date']);
        });
    }
};
