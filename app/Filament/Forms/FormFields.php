<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\TextColumn;
use Leandrocfe\FilamentPtbrFormFields\Cep;

class FormFields
{
    public static function note(bool $required = false): Textarea
    {
        return Textarea::make('note')
            ->label('Observação')
            ->required($required)
            ->maxLength(65535)
            ->columnSpanFull();
    }

    public static function name(bool $required = true): TextInput
    {
        return TextInput::make('name')
            ->label('Nome')
            ->required($required)
            ->columnSpanFull()
            ->maxLength(255);
    }

    public static function description(bool $required = true): TextInput
    {
        return TextInput::make('description')
            ->label('Descrição')
            ->required($required)
            ->columnSpanFull()
            ->maxLength(255);
    }


    public static function email(bool $required = false): TextInput
    {
        return TextInput::make('email')
            ->email()
            ->required($required)
            ->label('E-mail')
            ->maxLength(255);
    }

    public static function address(): Fieldset
    {
        return Fieldset::make('Endereço')
            ->relationship('address')
            ->schema([
                Cep::make('zip_code')
                    ->label('CEP')
                    ->live(onBlur: true),
                TextInput::make('street')->label('Rua')->columnSpan(1),
                TextInput::make('number')->label('N°'),
                TextInput::make('complement')->label('Complemento'),
                TextInput::make('reference')->label('Referência'),
                TextInput::make('district')->label('Bairro'),
                TextInput::make('city')->label('Cidade'),
                TextInput::make('state')->label('UF'),
            ])
            ->columns(4);
    }
}
