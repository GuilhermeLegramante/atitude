<?php

namespace App\Filament\Tables;

use Filament\Tables\Columns\TextColumn;

class TableColumns
{
    public static function createdAt(): TextColumn
    {
        return
            TextColumn::make('created_at')
            ->label('Criado em')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }

    public static function updatedAt(): TextColumn
    {
        return
            TextColumn::make('updated_at')
            ->label('Editado em')
            ->dateTime()
            ->sortable()
            ->toggleable(isToggledHiddenByDefault: true);
    }
}
