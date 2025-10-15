<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->integer('order')->nullable()->after('name'); // ou após o campo que fizer sentido
        });

        // Preencher turmas existentes com ordem sequencial por curso
        $courses = \App\Models\Course::all();

        foreach ($courses as $course) {
            $order = 1;
            foreach ($course->classes()->orderBy('created_at')->get() as $class) {
                $class->order = $order++;
                $class->save();
            }
        }
    }


    public function down(): void
    {
        Schema::table('classes', function (Blueprint $table) {
            $table->dropColumn('order');
        });
    }
};
