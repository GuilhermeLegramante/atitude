<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'assessment_id',
        'question_type_id',
        'question_text',
        'gabarito',
        'image_path',
        'audio_path',
        'pdf_path',
    ];

    public function assessment()
    {
        return $this->belongsTo(Assessment::class);
    }

    public function questionType()
    {
        return $this->belongsTo(QuestionType::class);
    }

    public function alternatives()
    {
        return $this->hasMany(Alternative::class);
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }
}
