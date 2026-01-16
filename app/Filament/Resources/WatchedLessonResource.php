<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WatchedLessonResource\Pages\ListWatchedLessons;
use App\Models\Lesson;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class WatchedLessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-play-circle';
    protected static ?string $navigationLabel = 'Aulas Assistidas';
    protected static ?string $pluralModelLabel = 'Aulas Assistidas';
    protected static ?string $navigationGroup = 'Relatórios';

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select([
                'lessons.*',
                'lesson_student.watched',
                'lesson_student.created_at as watched_at',
                'students.id as student_id',
                'users.name as student_name',
                'classes.name as class_name',
            ])
            ->join('lesson_student', 'lesson_student.lesson_id', '=', 'lessons.id')
            ->join('students', 'students.id', '=', 'lesson_student.student_id')
            ->join('users', 'users.id', '=', 'students.user_id')
            ->join('classes', 'classes.id', '=', 'lessons.class_id')
            ->where('lesson_student.watched', true);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student_name')
                    ->label('Aluno')
                    ->searchable(query: function ($query, $search) {
                        $query->where('users.name', 'like', "%{$search}%");
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Aula')
                    ->sortable()
                    ->searchable(query: function ($query, $search) {
                        $query->where('lessons.title', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('class_name')
                    ->label('Turma')
                    ->sortable()
                    ->searchable(query: function ($query, $search) {
                        $query->where('classes.name', 'like', "%{$search}%");
                    }),

                Tables\Columns\TextColumn::make('watched_at')
                    ->label('Assistido em')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('watched_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWatchedLessons::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }
}
