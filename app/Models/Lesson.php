<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'title',
        'description',
        'video_link',
        'note',
        'image_path',
        'order',
    ];

    public function class()
    {
        return $this->belongsTo(ClassModel::class);  // Cada lição pertence a uma classe
    }

    public function materials()
    {
        return $this->hasMany(Material::class);  // Uma aula pode ter vários materiais
    }

    public function assessments()
    {
        return $this->hasMany(Assessment::class);  // Uma aula pode ter várias atividades avaliativas
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'lesson_student')  // Relacionamento com Student através da tabela pivô 'lesson_student'
            ->withPivot('watched');  // Incluindo o campo 'watched' da tabela pivô
    }

    protected static function booted(): void
    {
        static::addGlobalScope('ordered', function (Builder $builder) {
            $builder->orderBy('order');
        });

        static::deleting(function ($lesson) {
            $lesson->students()->detach();
        });
    }

    public function getNextLessonAttribute()
    {
        $currentOrder = $this->order ?? 0;

        return $this->class?->lessons()
            ->where('order', '>', $currentOrder)
            ->orderBy('order', 'asc')
            ->first();
    }

    /**
     * Retorna se a aula foi assistida pelo aluno logado
     */
    public function getWatchedByStudentAttribute(): bool
    {
        $student = Auth::user()?->student;

        if (! $student) {
            return false;
        }

        // Verifica se existe na tabela pivot com watched = true
        return $this->students()
            ->where('student_id', $student->id)
            ->wherePivot('watched', true)
            ->exists();
    }
}
