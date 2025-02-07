<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;

class ClassForm
{
    public static function form(): array
    {
        return [
            Select::make('course_id')
                ->label('Curso')
                ->live()
                ->preload()
                ->searchable()
                ->required()
                ->relationship('course', 'name')
                ->createOptionForm(CourseForm::form()),
            FormFields::name(),
            FormFields::note(),
        ];
    }
}
