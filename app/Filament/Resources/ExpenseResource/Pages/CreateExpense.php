<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use App\Filament\Traits\HandlesRecurrences;
use App\Models\Expense;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    use HandlesRecurrences;

    protected static string $resource = ExpenseResource::class;
}
