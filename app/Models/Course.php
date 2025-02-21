<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'note',
    ];

    public function classes()
    {
        return $this->hasMany(ClassModel::class);  // Um curso pode ter várias classes
    }

    public function user() // Professor
    {
        return $this->belongsTo(User::class);
    }

}
