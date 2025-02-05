<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guardian extends Model
{
    use HasFactory;

    protected $fillable = [
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

    public function students()
    {
        return $this->hasMany(Student::class); // A guardian can have many students
    }

}
