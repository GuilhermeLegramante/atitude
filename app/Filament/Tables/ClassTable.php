<?php

namespace App\Filament\Tables;

use Filament\Tables\Columns\TextColumn;

class ClassTable
{
    public static function table(): array
    {
        return [
            TextColumn::make('name')
                ->label('Nome')
                ->searchable(),
            TextColumn::make('course.name')
                ->label('Curso')
                ->searchable(),
            TableColumns::createdAt(),
            TableColumns::updatedAt(),
        ];
    }
}
