<?php

namespace App\Filament\Resources\UserDictionaryResource\Pages;

use App\Filament\Resources\UserDictionaryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserDictionaries extends ListRecords
{
    protected static string $resource = UserDictionaryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
