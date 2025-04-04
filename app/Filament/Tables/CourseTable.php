<?php

namespace App\Filament\Tables;

use Filament\Tables\Columns\TextColumn;

class CourseTable
{
    public static function table(): array
    {
        return [
            TextColumn::make('name')
                ->label('Nome')
                ->searchable(),
            TextColumn::make('user.name')
                ->label('Professor')
                ->searchable(),
            TableColumns::createdAt(),
            TableColumns::updatedAt(),
        ];
    }
}
