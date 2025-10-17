<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Text extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'language', 'user_id'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
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
