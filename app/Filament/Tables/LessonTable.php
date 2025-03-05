<?php

namespace App\Filament\Tables;

use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\DB;

class LessonTable
{
    public static function table(): array
    {
        return [
            IconColumn::make('video_link')
                ->label('Vídeo')
                ->icon('heroicon-o-video-camera')
                ->action(
                    \Hugomyb\FilamentMediaAction\Tables\Actions\MediaAction::make('Vídeo')
                        ->media(fn($record) => $record->video_link),
                ),
            TextColumn::make('title')
                ->label('Título')
                ->searchable(),
            TextColumn::make('class.course.name')
                ->label('Curso')
                ->sortable(),
            TextColumn::make('class.name')
                ->label('Turma')
                ->sortable(),
            TableColumns::createdAt(),
            TableColumns::updatedAt(),
        ];
    }
}
