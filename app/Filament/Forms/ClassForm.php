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
                ->columnSpanFull()
                ->relationship('course', 'name')
                ->createOptionForm(CourseForm::form()),
            Select::make('student_id')
                ->label('Alunos')
                ->multiple()
                ->preload()
                ->searchable()
                ->columnSpanFull()
                ->relationship('students', 'name')
                ->createOptionForm(StudentForm::form(hasSection: false)),
            FormFields::name(),
            FormFields::note(),
        ];
    }
}
