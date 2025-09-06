<?php

use App\Models\ClassModel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->integer('order')->nullable()->after('title'); // coluna para sequência
        });

        // Preencher aulas existentes com ordem sequencial por classe
        $classes = ClassModel::all();
        foreach ($classes as $class) {
            $order = 1;
            foreach ($class->lessons()->orderBy('created_at')->get() as $lesson) {
                $lesson->order = $order++;
                $lesson->save();
            }
        }
    }

    public function down(): void
    {
        Schema::table('lessons', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
