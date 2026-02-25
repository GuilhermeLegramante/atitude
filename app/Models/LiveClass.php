<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
    use HasFactory;

    protected $fillable = [
        'language',
        'weekday',
        'time',
        'link',
        'description',
        'active',
    ];

    public function scopeOrdered($query)
    {
        return $query->orderByRaw("
        FIELD(weekday, 
        'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado')
    ")->orderBy('time');
    }
}
