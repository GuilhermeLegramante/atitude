<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassModel extends Model
{
    use HasFactory;

    protected $table = 'classes';

    protected $fillable = [
        'course_id',
        'name',
        'note',
    ];

    public function students()
    {
        return $this->belongsToMany(Student::class, 'student_class');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);  // Uma classe pode ter várias aulas
    }

    public function course()
    {
        return $this->belongsTo(Course::class);  // Cada classe pertence a um curso
    }
}
