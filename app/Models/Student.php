<?php

namespace App\Models;

use Faker\Provider\ar_EG\Address;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guardian_id',
        'name',
        'photo',
        'birth_date',
        'rg',
        'cpf',
        'gender',
        'address_id',
        'email',
        'phone',
        'note',
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function classes()
    {
        return $this->belongsToMany(ClassModel::class); // ClassModel to avoid conflict with reserved 'class'
    }

    public function watchedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'lesson_student')->withPivot('watched');
    }

    public function guardian()
    {
        return $this->belongsTo(Guardian::class); // Relationship to the guardian
    }

}
