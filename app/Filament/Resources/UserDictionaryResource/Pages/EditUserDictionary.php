<?php

namespace App\Filament\Resources\UserDictionaryResource\Pages;

use App\Filament\Resources\UserDictionaryResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserDictionary extends EditRecord
{
    protected static string $resource = UserDictionaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
