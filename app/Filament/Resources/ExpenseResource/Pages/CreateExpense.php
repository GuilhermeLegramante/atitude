<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use App\Models\Expense;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateExpense extends CreateRecord
{
    protected static string $resource = ExpenseResource::class;

    protected function afterCreate(): void
    {
        $expense = $this->record;

        if ($expense->is_recurring && $expense->recurrence_type && $expense->recurrence_end_date) {
            $this->createRecurrences($expense);
        }
    }

    private function createRecurrences(Expense $expense): void
    {
        $interval = match ($expense->recurrence_type) {
            'diario' => '1 day',
            'mensal' => '1 month',
            'trimestral' => '3 months',
            'semestral' => '6 months',
            'anual' => '1 year',
            default => null,
        };

        if (!$interval) {
            return;
        }

        $startDate = Carbon::parse($expense->due_date);
        $endDate = Carbon::parse($expense->recurrence_end_date);

        $nextDate = $startDate->copy()->add($interval);

        while ($nextDate->lessThanOrEqualTo($endDate)) {
            // Evita duplicar parcelas
            if (!Expense::where('parent_id', $expense->id)->whereDate('due_date', $nextDate)->exists()) {
                Expense::create([
                    'expense_category_id' => $expense->expense_category_id,
                    'description' => $expense->description,
                    'amount' => $expense->amount,
                    'due_date' => $nextDate,
                    'status' => 'aberto',
                    'attachments' => $expense->attachments,
                    'is_recurring' => false,
                    'recurrence_type' => null,
                    'recurrence_end_date' => null,
                    'parent_id' => $expense->id,
                ]);
            }
            $nextDate->add($interval);
        }
    }
}
