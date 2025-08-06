<?php

namespace App\Filament\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

trait HandlesRecurrences
{
    protected function handleRecurrences(Model $model): void
    {
        if (!($model->is_recurring && $model->recurrence_type && $model->recurrence_end_date)) {
            return;
        }

        $interval = match ($model->recurrence_type) {
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

        $startDate = Carbon::parse($model->due_date);
        $endDate = Carbon::parse($model->recurrence_end_date);
        $nextDate = $startDate->copy()->add($interval);

        $modelClass = get_class($model);

        while ($nextDate->lessThanOrEqualTo($endDate)) {
            if (!$modelClass::where('parent_id', $model->id)->whereDate('due_date', $nextDate)->exists()) {
                $modelClass::create(
                    $this->buildRecurringData($model, $nextDate)
                );
            }

            $nextDate->add($interval);
        }
    }

    protected function buildRecurringData(Model $model, Carbon $nextDate): array
    {
        if ($model instanceof \App\Models\Payment) {
            return [
                'student_id' => $model->student_id,
                'description' => $model->description,
                'amount' => $model->amount,
                'due_date' => $nextDate,
                'status' => 'aberto',
                'is_recurring' => false,
                'recurrence_type' => null,
                'recurrence_end_date' => null,
                'payment_method' => $model->payment_method,
                'parent_id' => $model->id,
            ];
        }

        if ($model instanceof \App\Models\Expense) {
            return [
                'expense_category_id' => $model->expense_category_id,
                'description' => $model->description,
                'amount' => $model->amount,
                'due_date' => $nextDate,
                'status' => 'aberto',
                'attachments' => $model->attachments,
                'is_recurring' => false,
                'recurrence_type' => null,
                'recurrence_end_date' => null,
                'parent_id' => $model->id,
            ];
        }

        throw new \Exception('Modelo não suportado para recorrência');
    }
}
