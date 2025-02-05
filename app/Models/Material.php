<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'name',
        'description',
        'type',
        'url',
        'note',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);  // Cada material é de uma aula (lesson)
    }

   
}
