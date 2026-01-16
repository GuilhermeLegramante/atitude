<?php

namespace App\Filament\Resources\WatchedLessonResource\Pages;

use App\Filament\Resources\WatchedLessonResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListWatchedLessons extends ListRecords
{
    protected static string $resource = WatchedLessonResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
