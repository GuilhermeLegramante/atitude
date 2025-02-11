<?php

namespace App\Filament\Forms;

use App\Models\Lesson;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

class LessonForm
{
    public static function form(): array
    {
        return [
            Group::make([
                Section::make([
                    Select::make('class_id')
                        ->label('Turma')
                        ->live()
                        ->preload()
                        ->searchable()
                        ->required()
                        ->columnSpanFull()
                        ->relationship('class', 'name')
                        ->createOptionForm(ClassForm::form()),

                    TextInput::make('title')
                        ->label('Título')
                        ->required()
                        ->columnSpanFull()
                        ->maxLength(255),

                    Textarea::make('description')
                        ->label('Descrição')
                        ->rows(2)
                        ->columnSpanFull(),
                    FormFields::note(),
                ])->columns(2),
            ])->columnSpan(['sm' => 3]),

            Group::make([
                Section::make([
                    Placeholder::make('created_at')
                        ->label('Enviada em')
                        ->content(function (?Lesson $record): string {
                            return $record->created_at ? "{$record->created_at->format(config('filament-logger.datetime_format', 'd/m/Y H:i:s'))}" : '-';
                        }),
                ])
            ])->hiddenOn('create'),
            Section::make(
                [
                    TextInput::make('video_link')
                        ->label('Link do vídeo')
                        ->columnSpanFull()
                        ->required()
                        ->maxLength(255)
                        ->visibleOn('create'),
                    TextInput::make('video_link')
                        ->label('Link do vídeo')
                        ->columnSpanFull()
                        ->prefixAction(
                            fn($record) =>
                            \Hugomyb\FilamentMediaAction\Forms\Components\Actions\MediaAction::make('Assistir')
                                ->icon('heroicon-o-video-camera')
                                ->media($record->video_link)

                        )
                        ->required()
                        ->maxLength(255)
                        ->hiddenOn('create'),

                ]
            )
        ];
    }
    // Esperar resposta no github pra ver o uso em forms
    // Resolveu comentando a linha 31 da trait \hugomyb\filament-media-action\src\Concerns\HasMedia.php
    // MediaAction::make('tutorial')
    //     ->iconButton()
    //     ->icon('heroicon-o-video-camera')
    //     ->media('https://www.youtube.com/watch?v=rN9XI9KCz0c&list=PL6tf8fRbavl3jfL67gVOE9rF0jG5bNTMi'),
}
