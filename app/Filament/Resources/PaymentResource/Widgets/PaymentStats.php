<?php

namespace App\Filament\Resources\PaymentResource\Widgets;

use App\Models\Payment;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PaymentStats extends BaseWidget
{
    public ?string $mode = 'monthly';
    public ?string $selectedDate = null;

    protected function getStats(): array
    {
        $date = $this->selectedDate ? Carbon::parse($this->selectedDate) : now();

        $start = $this->mode === 'yearly'
            ? $date->copy()->startOfYear()
            : $date->copy()->startOfMonth();

        $end = $this->mode === 'yearly'
            ? $date->copy()->endOfYear()
            : $date->copy()->endOfMonth();

        return [
            Stat::make('Total a Receber', 'R$ ' . number_format(
                Payment::whereBetween('due_date', [$start, $end])
                    ->where('status', 'aberto')
                    ->sum('amount'),
                2,
                ',',
                '.'
            ))
                ->description('Pagamentos em aberto')
                ->color('warning'),

            Stat::make('Recebido', 'R$ ' . number_format(
                Payment::whereBetween('due_date', [$start, $end])
                    ->where('status', 'pago')
                    ->sum('amount'),
                2,
                ',',
                '.'
            ))
                ->description('Pagamentos recebidos')
                ->color('success'),

            Stat::make('Vencidos', 'R$ ' . number_format(
                Payment::whereBetween('due_date', [$start, $end])
                    ->where('status', 'vencido')
                    ->sum('amount'),
                2,
                ',',
                '.'
            ))
                ->description('Pagamentos vencidos')
                ->color('danger'),
        ];
    }
}
