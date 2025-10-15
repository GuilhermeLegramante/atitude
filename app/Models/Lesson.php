<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

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
    }

    // Acesso à próxima aula na mesma turma
    public function getNextLessonAttribute()
    {
        return $this->class?->lessons()
            ->where('order', '>', $this->order) // próxima aula
            ->orderBy('order', 'asc')
            ->first(); // retorna null se não houver próxima
    }
}
