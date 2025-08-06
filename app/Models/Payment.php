<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'description',
        'amount',
        'status',
        'due_date',
        'payment_date',
        'payment_method',
        'is_recurring',
        'recurrence_type',
        'recurrence_end_date',
    ];

    protected $casts = [
        'due_date' => 'date',
        'payment_date' => 'date',
        'is_recurring' => 'boolean',
        'recurrence_end_date' => 'date',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }
}
