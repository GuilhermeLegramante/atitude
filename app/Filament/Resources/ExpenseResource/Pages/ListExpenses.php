<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use App\Filament\Resources\ExpenseResource\Widgets\ExpenseStats;
use App\Models\Expense;
use Carbon\Carbon;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Pages\Concerns\ExposesTableToWidgets;


class ListExpenses extends ListRecords
{
    use ExposesTableToWidgets;

    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('gerarRecorrencia')
                ->label('Gerar recorrências')
                ->icon('heroicon-m-arrow-path')
                ->color('primary')
                ->requiresConfirmation()
                ->action(function () {
                    $recorrentes = Expense::where('is_recurring', true)->get();

                    foreach ($recorrentes as $despesa) {
                        $novaData = Carbon::parse($despesa->due_date)->addMonth()->startOfDay();

                        // Verifica se já existe uma despesa igual no próximo mês
                        $existe = Expense::where('is_recurring', true)
                            ->where('description', $despesa->description)
                            ->where('amount', $despesa->amount)
                            ->whereDate('due_date', $novaData)
                            ->exists();

                        if (! $existe) {
                            Expense::create([
                                'expense_category_id' => $despesa->expense_category_id,
                                'description'         => $despesa->description,
                                'amount'              => $despesa->amount,
                                'due_date'            => $novaData,
                                'status'              => 'aberto',
                                'is_recurring'        => true,
                            ]);
                        }
                    }

                    Notification::make()
                        ->title('Sucesso')
                        ->body('Recorrências geradas com sucesso!')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            ExpenseStats::class,
        ];
    }
}
