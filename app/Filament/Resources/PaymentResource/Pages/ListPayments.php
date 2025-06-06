<?php

namespace App\Filament\Resources\PaymentResource\Pages;

use App\Filament\Resources\PaymentResource;
use App\Filament\Resources\PaymentResource\Widgets\PaymentStats;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;

class ListPayments extends ListRecords
{
    protected static string $resource = PaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Action::make('calcularVencidos')
                ->label('Verificar vencimentos')
                ->icon('heroicon-m-calendar')
                ->color('danger')
                ->requiresConfirmation()
                ->action(function () {
                    \App\Models\Payment::where('status', 'aberto')
                        ->whereDate('due_date', '<', now()->startOfDay())
                        ->update(['status' => 'vencido']);

                    Notification::make()
                        ->title('Sucesso')
                        ->body('Pagamentos vencidos atualizados com sucesso.')
                        ->success()
                        ->send();
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            PaymentStats::class,
        ];
    }
}
