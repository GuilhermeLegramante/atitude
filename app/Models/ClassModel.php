<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'course_id',
        'name',
        'note',
        'order',
    ];

    protected $casts = [
        'order' => 'integer',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_class', 'class_id', 'student_id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class, 'class_id');  // Uma classe pode ter várias aulas
    }

    public function course()
    {
        return $this->belongsTo(Course::class);  // Cada classe pertence a um curso
    }

    public function assessment()
    {
        return $this->hasMany(Assessment::class);
    }

    protected static function booted()
    {
        static::addGlobalScope('ordered', function (Builder $builder) {
            $builder->orderBy('order');
        });
    }

    public function getTotalLessonsAttribute()
    {
        return $this->lessons()->count();
    }

    /**
     * Retorna o percentual de aulas assistidas pelo aluno logado nesta turma.
     */
    public function getProgressAttribute()
    {
        $student = auth()->user()?->student; // pega o student relacionado ao usuário logado

        if (!$student) {
            return 0;
        }

        $totalLessons = $this->lessons->count();

        if ($totalLessons === 0) {
            return 0;
        }

        $watchedLessons = $this->lessons->filter(function ($lesson) use ($student) {
            return $lesson->students->where('id', $student->id)->first()?->pivot->watched ?? false;
        })->count();

        return round(($watchedLessons / $totalLessons) * 100);
    }

    /**
     * Verifica se o aluno completou todas as avaliações deste módulo específico.
     */
    public function isCompletedByStudent($userId = null)
    {
        // Se não passar ID (ex: no Blade), usa o do utilizador logado
        $userId = $userId ?: auth()->id();

        if (auth()->user()?->hasFullAccess()) {
            return true;
        }

        if (!$userId) return false;

        // 1. Pega os IDs de todas as avaliações vinculadas às aulas deste módulo
        $assessmentIds = $this->lessons()->with('assessments')->get()
            ->pluck('assessments.*.id')
            ->flatten()
            ->unique();

        // Se o módulo não tiver provas, considera-se concluído
        if ($assessmentIds->isEmpty()) {
            return true;
        }

        // 2. Conta quantas questões existem nessas avaliações
        $totalQuestions = \App\Models\Question::whereIn('assessment_id', $assessmentIds)->count();

        if ($totalQuestions === 0) return true;

        // 3. Conta quantas respostas o aluno específico enviou para estas questões
        $completedAnswers = \App\Models\Answer::where('user_id', $userId)
            ->whereIn('question_id', function ($query) use ($assessmentIds) {
                $query->select('id')->from('questions')
                    ->whereIn('assessment_id', $assessmentIds);
            })
            ->count();

        // O módulo só é considerado completo se ele respondeu a TODAS as questões
        return $completedAnswers >= $totalQuestions;
    }
}
