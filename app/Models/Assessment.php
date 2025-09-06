<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'lesson_id',
        'visible',
        'audio_path',
        'image_path',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
