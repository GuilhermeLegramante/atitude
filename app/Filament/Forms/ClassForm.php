<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Validation\Rule;

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
            TextInput::make('order')
                ->label('Ordem da Aula')
                ->numeric()
                ->required()
                ->rule(function ($record) {
                    return Rule::unique('classes', 'order')
                        ->where('course_id', $record?->course_id)
                        ->ignore($record?->id);
                })
                ->hiddenOn('view')
                ->helperText('Número único para cada aula dentro da turma'),
            FormFields::note(),
        ];
    }
}
