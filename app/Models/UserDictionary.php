<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDictionary extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'word', 'translation'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($entry) {
            if (!auth()->check()) {
                throw new \Exception('Usuário não autenticado.');
            }
            $entry->user_id = auth()->id();
        });
    }
}
