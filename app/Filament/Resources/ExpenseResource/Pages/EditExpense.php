<?php

namespace App\Filament\Resources\ExpenseResource\Pages;

use App\Filament\Resources\ExpenseResource;
use App\Filament\Traits\HandlesRecurrences;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditExpense extends EditRecord
{
    use HandlesRecurrences;
    protected static string $resource = ExpenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
