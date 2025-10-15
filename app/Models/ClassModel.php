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
}
