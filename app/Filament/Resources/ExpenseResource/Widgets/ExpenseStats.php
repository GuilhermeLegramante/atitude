<?php

namespace App\Filament\Resources\ExpenseResource\Widgets;

use App\Models\Expense;
use Carbon\Carbon;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Livewire\Attributes\On;

class ExpenseStats extends BaseWidget
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

        $label = $this->mode === 'yearly'
            ? "Despesas de {$date->format('Y')}"
            : "Despesas de {$date->format('m/Y')}";

        return [
            Stat::make('Total Previsto (Mês)', 'R$ ' . number_format(
                Expense::whereBetween('due_date', [$start, $end])->sum('amount'),
                2,
                ',',
                '.'
            ))
                ->description($label)
                ->color('warning'),

            Stat::make('Despesas Pagas (Mês)', 'R$ ' . number_format(
                Expense::whereBetween('due_date', [$start, $end])
                    ->where('status', 'pago')
                    ->sum('amount'),
                2,
                ',',
                '.'
            ))
                ->description('Despesas quitadas')
                ->color('success'),

            Stat::make('Despesas Vencidas (Mês)', 'R$ ' . number_format(
                Expense::whereBetween('due_date', [$start, $end])
                    ->where('status', 'vencido')
                    ->sum('amount'),
                2,
                ',',
                '.'
            ))
                ->description('Despesas vencidas')
                ->color('danger'),
        ];
    }

    protected static ?string $pollingInterval = null;
}
