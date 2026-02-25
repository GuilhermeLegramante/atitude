<?php

namespace App\Filament\Tables;

use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;

class StudentTable
{
    public static function table(): array
    {
        return [
            SelectColumn::make('language')
                ->label('Idioma')
                ->options([
                    'en' => 'Inglês',
                    'es' => 'Espanhol',
                    'both' => 'Ambos',
                ]),
            // ImageColumn::make('photo')
            //     ->size(50)
            //     ->circular()
            //     ->label('Foto')
            //     ->alignCenter(),
            TextColumn::make('name')
                ->label('Nome')
                ->searchable(),
            TextColumn::make('cpf')
                ->label('CPF')
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
            TextColumn::make('rg')
                ->label('RG')
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
            TextColumn::make('birth_date')
                ->label('Data de Nascimento')
                ->toggleable(isToggledHiddenByDefault: false)
                ->date()
                ->sortable(),
            TextColumn::make('phone')
                ->label('Telefone')
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
            TextColumn::make('gender')
                ->toggleable(isToggledHiddenByDefault: false)
                ->label('Gênero')
                ->searchable(),
            TextColumn::make('email')
                ->label('E-mail')
                ->toggleable(isToggledHiddenByDefault: false)
                ->searchable(),
            TextColumn::make('guardian.name')
                ->label('Responsável')
                ->toggleable(isToggledHiddenByDefault: true)
                ->sortable(),
            TableColumns::createdAt(),
            TableColumns::updatedAt(),
        ];
    }
}
