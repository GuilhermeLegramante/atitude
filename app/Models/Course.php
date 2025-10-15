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
        'language'
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
    public function getProgressAttribute()
    {
        $student = Auth::user(); // ou Auth::user()->student se você tiver relacionamento

        if (!$student) {
            return 0;
        }

        $totalLessons = $this->classes->sum(fn($class) => $class->lessons->count());

        if ($totalLessons === 0) {
            return 0;
        }

        $watchedLessons = 0;

        foreach ($this->classes as $class) {
            foreach ($class->lessons as $lesson) {
                if ($lesson->students->where('id', $student->id)->first()?->pivot->watched) {
                    $watchedLessons++;
                }
            }
        }

        return round(($watchedLessons / $totalLessons) * 100);
    }
}
