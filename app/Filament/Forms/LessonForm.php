<?php

namespace App\Filament\Forms;

use App\Models\Lesson;
use Filament\Facades\Filament;
use Filament\Forms\Components\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ViewField;
use Illuminate\Validation\Rule;

class LessonForm
{
    public static function form(): array
    {
        return [
            Section::make([
                ViewField::make('lesson_card')
                    ->view('view-lesson-field')
                    ->extraAttributes(['class' => 'w-full']),
                Select::make('class_id')
                    ->label('Turma')
                    ->live()
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull()
                    ->relationship('class', 'name')
                    ->hiddenOn('view')
                    ->createOptionForm(ClassForm::form()),

                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->columnSpanFull()
                    ->hiddenOn('view')
                    ->maxLength(255),

                TextInput::make('order')
                    ->label('Ordem da Aula')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->rule(function ($record) {
                        return Rule::unique('lessons', 'order')
                            ->where('class_id', $record?->class_id)
                            ->ignore($record?->id);
                    })
                    ->hiddenOn('view')
                    ->helperText('Número único para cada aula dentro da turma'),

                Textarea::make('description')
                    ->label('Descrição')
                    ->rows(2)
                    ->hiddenOn('view')
                    ->columnSpanFull(),
                FileUpload::make('image_path')
                    ->label('Thumbnail da Aula')
                    ->disk('public')
                    ->directory('lessons')
                    ->hiddenOn('view')
                    ->image()
                    ->maxSize(2048) // 2MB
                    ->nullable(),
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
                Toggle::make('watched')
                    ->label('Aula assistida')
                    ->default(function ($record) {
                        return auth()->user()
                            ?->watchedLessons()
                            ->where('lesson_id', $record->id)
                            ->first()
                            ?->pivot
                            ?->watched;
                    })
                    ->afterStateUpdated(function ($state, callable $set, $get, $record) {
                        $student = auth()->user();
                        if ($student && $record) {
                            $student->watchedLessons()->updateExistingPivot($record->id, ['watched' => $state]);
                        }
                    })
                    ->dehydrated(false)
                    ->columnSpanFull(),
                FormFields::note(),

            ])->columns(2),

            // Group::make([
            //     Section::make([
            //         Placeholder::make('created_at')
            //             ->label('Enviada em')
            //             ->content(function (?Lesson $record): string {
            //                 return $record->created_at ? "{$record->created_at->format(config('filament-logger.datetime_format', 'd/m/Y H:i:s'))}" : '-';
            //             }),
            //     ])
            // ])->hiddenOn(['create', 'view']),
            // Section::make(
            //     [
            //         TextInput::make('video_link')
            //             ->label('Link do vídeo')
            //             ->columnSpanFull()
            //             ->required()
            //             ->maxLength(255)
            //             ->visibleOn('create'),
            //         TextInput::make('video_link')
            //             ->label('Link do vídeo')
            //             ->columnSpanFull()
            //             ->prefixAction(
            //                 fn($record) =>
            //                 \Hugomyb\FilamentMediaAction\Forms\Components\Actions\MediaAction::make('Assistir')
            //                     ->icon('heroicon-o-video-camera')
            //                     ->media($record->video_link)
            //             )
            //             ->required()
            //             ->maxLength(255)
            //             ->hiddenOn('create'),

            //     ]
            // )
        ];
    }
    // Esperar resposta no github pra ver o uso em forms
    // Resolveu comentando a linha 31 da trait \hugomyb\filament-media-action\src\Concerns\HasMedia.php
    // MediaAction::make('tutorial')
    //     ->iconButton()
    //     ->icon('heroicon-o-video-camera')
    //     ->media('https://www.youtube.com/watch?v=rN9XI9KCz0c&list=PL6tf8fRbavl3jfL67gVOE9rF0jG5bNTMi'),
}
