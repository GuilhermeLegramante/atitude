<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    // Campos que podem ser preenchidos em massa
    protected $fillable = ['question_id', 'alternative_text', 'is_correct'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}

