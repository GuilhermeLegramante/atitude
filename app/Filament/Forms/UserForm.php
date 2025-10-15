<?php

namespace App\Filament\Forms;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function form(): array
    {
        return [
            FormFields::name(),
            FileUpload::make('avatar')
                ->label('Foto de Perfil')
                ->image() // garante que só sejam imagens
                ->directory('avatars') // pasta onde será salvo
                ->maxSize(1024) // 1MB
                ->nullable()
                ->columnSpan('full'),
            TextInput::make('email')
                ->required()
                ->unique(ignoreRecord: true)
                ->label('Email')
                ->email(),
            TextInput::make('password')
                ->label('Senha')
                ->password()
                ->revealable()
                ->required()
                ->rule('min:4')
                ->dehydrateStateUsing(fn($state) => Hash::make($state))
                ->same('passwordConfirmation')
                ->validationAttribute('senha'),
            TextInput::make('passwordConfirmation')
                ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
                ->password()
                ->required()
                ->revealable()
                ->dehydrated(false),
            Select::make('roles')
                ->label('Perfil')
                ->relationship('roles', 'name')
                ->preload()
                ->searchable(),
        ];
    }
}
