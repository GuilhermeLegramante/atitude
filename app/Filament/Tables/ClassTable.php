<?php

namespace App\Filament\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\TextInputColumn;

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
            TextInputColumn::make('order')
                ->sortable()
                ->label('Ordem'),
            TableColumns::createdAt(),
            TableColumns::updatedAt(),
        ];
    }
}
