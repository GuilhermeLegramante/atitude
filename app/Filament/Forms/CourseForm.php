<?php

namespace App\Filament\Forms;

use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;

class CourseForm
{
    public static function form(): array
    {
        return [
            FormFields::name(),

            FormFields::description(required: false),

            FileUpload::make('image_path')
                ->label('Thumbnail do Curso')
                ->disk('public')
                ->directory('courses')
                ->image()
                ->maxSize(2048) // 2MB
                ->nullable(),

            Select::make('user_id')
                ->label('Professor')
                ->options(function () {
                    // Filtra usuários com o perfil super_admin ou professor
                    return User::whereIn('perfil', ['super_admin', 'professor'])
                        ->pluck('name', 'id'); // Pluck irá pegar 'name' como label e 'id' como valor
                })
                ->columnSpanFull()
                ->live()
                ->preload()
                ->searchable()
                ->required()
                ->relationship('user', 'name')
                ->createOptionForm(UserForm::form()),

            Select::make('language')
                ->label('Idioma')
                ->options([
                    'en' => 'Inglês',
                    'es' => 'Espanhol',
                ])
                ->default('en')
                ->required(),

            FormFields::note(),
        ];
    }
}
