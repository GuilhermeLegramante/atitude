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

    public function isReleasedForStudent($studentId)
    {
        // Busca o curso anterior do mesmo idioma baseado no ID ou 'created_at'
        // Se você tiver muitos cursos, o ideal é adicionar uma coluna 'order' na tabela courses
        $previousCourse = \App\Models\Course::where('language', $this->language)
            ->where('id', '<', $this->id)
            ->orderBy('id', 'desc')
            ->first();

        // Se não existir curso anterior, este é o primeiro e está liberado
        if (!$previousCourse) {
            return true;
        }

        // Verifica o progresso do curso anterior (deve ser 100)
        // Você já usa $course->progress no Blade, certifique-se que essa lógica reflete a conclusão real
        return $this->calculateProgress($studentId) >= 100;
    }
}
