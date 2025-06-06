<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'expense_category_id',
        'description',
        'amount',
        'due_date',
        'payment_date',
        'status',
        'attachments',
        'is_recurring',
        'recurrence_type',
        'recurrence_end_date',
    ];

    protected $casts = [
        'attachments' => 'array',
        'due_date' => 'date',
        'payment_date' => 'date',
        'recurrence_end_date' => 'date',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ExpenseCategory::class, 'expense_category_id');
    }
}
