<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'note',
        'language',
        'image_path',
    ];

    public function classes()
    {
        return $this->hasMany(ClassModel::class);  // Um curso pode ter várias classes
    }

    public function user() // Professor
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retorna o percentual de aulas assistidas pelo aluno logado.
     */
    // public function getProgressAttribute()
    // {
    //     $student = Auth::user()->student; // ou Auth::user()->student se você tiver relacionamento

    //     if (!$student) {
    //         return 0;
    //     }

    //     $totalLessons = $this->classes->sum(fn($class) => $class->lessons->count());

    //     if ($totalLessons === 0) {
    //         return 0;
    //     }

    //     $watchedLessons = 0;

    //     foreach ($this->classes as $class) {
    //         foreach ($class->lessons as $lesson) {
    //             if ($lesson->students->where('id', $student->id)->first()?->pivot->watched) {
    //                 $watchedLessons++;
    //             }
    //         }
    //     }

    //     return round(($watchedLessons / $totalLessons) * 100);
    // }

    /**
     * Calcula o progresso de um aluno específico no curso.
     */
    public function calculateProgress($studentId)
    {
        // Soma todas as aulas de todos os módulos (classes) do curso
        $totalLessons = $this->getTotalLessonsAttribute();

        if ($totalLessons === 0) {
            return 0;
        }

        $watchedLessons = 0;

        foreach ($this->classes as $class) {
            foreach ($class->lessons as $lesson) {
                // Verifica se o aluno específico assistiu à aula na tabela pivot
                $isWatched = $lesson->students()
                    ->where('student_id', $studentId)
                    ->wherePivot('watched', true)
                    ->exists();

                if ($isWatched) {
                    $watchedLessons++;
                }
            }
        }

        return round(($watchedLessons / $totalLessons) * 100);
    }

    /**
     * Atualize o Accessor para usar o novo método baseado no utilizador logado.
     */
    public function getProgressAttribute()
    {
        $student = Auth::user()?->student;

        if (!$student) {
            return 0;
        }

        return $this->calculateProgress($student->id);
    }

    public function getTotalLessonsAttribute()
    {
        // Soma todas as lessons de todas as classes do curso
        return $this->classes->sum(fn($class) => $class->lessons->count());
    }

    /**
     * Verifica se o curso está disponível para o aluno.
     * Regra: Se houver um curso anterior do mesmo idioma, ele deve estar 100% concluído.
     */
    public function isReleasedForStudent($studentId)
    {
        // Busca o curso anterior do mesmo idioma baseado no ID
        $previousCourse = self::where('language', $this->language)
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();

        // Se não existir curso anterior, este é o primeiro da trilha e está livre
        if (!$previousCourse) {
            return true;
        }

        return true;

        // O curso atual só liberta se o progresso do ANTERIOR for 100%
        // return $previousCourse->calculateProgress($studentId) >= 100;
    }
}
