<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;

class LessonForm
{
    public static function form(): array
    {
        return [
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
            FormFields::description(),
            TextInput::make('video_link')
                ->label('Link do Vídeo')
                ->url()
                ->maxLength(255),
            FormFields::note(),
        ];
    }
    // Esperar resposta no github pra ver o uso em forms
    // Resolveu comentando a linha 31 da trait \hugomyb\filament-media-action\src\Concerns\HasMedia.php
    // MediaAction::make('tutorial')
    //     ->iconButton()
    //     ->icon('heroicon-o-video-camera')
    //     ->media('https://www.youtube.com/watch?v=rN9XI9KCz0c&list=PL6tf8fRbavl3jfL67gVOE9rF0jG5bNTMi'),
}
