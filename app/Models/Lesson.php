<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'class_id',
        'title',
        'description',
        'video_link',
        'note',
    ];

    public function classModel()
    {
        return $this->belongsTo(ClassModel::class);  // Cada lição pertence a uma classe
    }

    public function materials()
    {
        return $this->hasMany(Material::class);  // Uma aula pode ter vários materiais
    }

    public function students()
    {
        return $this->belongsToMany(Student::class, 'lesson_student')  // Relacionamento com Student através da tabela pivô 'lesson_student'
            ->withPivot('watched');  // Incluindo o campo 'watched' da tabela pivô
    }
}
