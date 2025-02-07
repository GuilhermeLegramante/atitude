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
                ->relationship('class', 'name')
                ->createOptionForm(ClassForm::form()),
            TextInput::make('title')
                ->label('Título')
                ->required()
                ->maxLength(255),
            FormFields::description(),
            TextInput::make('video_link')
                ->maxLength(255)
                ->live()
                ->afterStateUpdated(fn($state, $get, $set) => $set('video_iframe', $get('video_link'))),


            // ViewField::make('video_iframe')
            //     ->view('components.video-iframe')
            //     ->columnSpanFull(),

            FormFields::note(),
        ];
    }
    // Esperar resposta no github
    // MediaAction::make('tutorial')
    //     ->iconButton()
    //     ->icon('heroicon-o-video-camera')
    //     ->media('https://www.youtube.com/watch?v=rN9XI9KCz0c&list=PL6tf8fRbavl3jfL67gVOE9rF0jG5bNTMi'),
}
