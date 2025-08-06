<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Models\Payment;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPayment extends EditRecord
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $payment = $this->record;

        if ($payment->is_recurring && $payment->recurrence_type && $payment->recurrence_end_date) {
            $this->createRecurrences($payment);
        }
    }

    private function createRecurrences(Payment $payment): void
    {
        $interval = match ($payment->recurrence_type) {
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

        $startDate = Carbon::parse($payment->due_date);
        $endDate = Carbon::parse($payment->recurrence_end_date);

        // Gerar recurrences até a data final
        $nextDate = $startDate->copy()->add($interval);

        while ($nextDate->lessThanOrEqualTo($endDate)) {
            // Evita duplicar
            if (!Payment::where('parent_id', $payment->id)->whereDate('due_date', $nextDate)->exists()) {
                Payment::create([
                    'student_id' => $payment->student_id,
                    'description' => $payment->description,
                    'amount' => $payment->amount,
                    'due_date' => $nextDate,
                    'status' => 'aberto',
                    'is_recurring' => false,
                    'recurrence_type' => null,
                    'recurrence_end_date' => null,
                    'payment_method' => $payment->payment_method,
                    'parent_id' => $payment->id,
                ]);
            }

            $nextDate->add($interval);
        }
    }
}
