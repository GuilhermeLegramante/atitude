<?php

namespace App\Filament\Pages\Auth;

use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register as AuthRegister;
use Illuminate\Support\Facades\Hash;

class Register extends AuthRegister
{
    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/register.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule('min:4')
            ->dehydrateStateUsing(fn($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute('senha');
    }
}
