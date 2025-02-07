<?php

namespace App\Filament\Forms;

use Carbon\Carbon;
use Closure;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class StudentForm
{
    public static function form(bool $hasSection = true): array
    {
        $formData = [
            FormFields::name(),
            FileUpload::make('photo')
                ->label('Foto')
                ->avatar()
                ->previewable()
                ->openable()
                ->downloadable()
                ->moveFiles()
                ->imageEditor(),
            FormFields::email(),
            Select::make('user_id')
                ->label('Usuário')
                ->live()
                ->preload()
                ->searchable()
                ->required()
                ->relationship('user', 'name')
                ->createOptionForm(UserForm::form()),
            DatePicker::make('birth_date')
                ->maxDate(now())
                ->live(debounce: 1000)
                ->label('Data de Nascimento')
                ->rules('before_or_equal:' . now()->toDateString())
                ->afterStateUpdated(function ($state, $set) {
                    // Verifique se a data de nascimento é menor que 18 anos
                    $birthDate = Carbon::parse($state);
                    $age = $birthDate->age;

                    // Se for menor de 18, mostre o campo 'guardian_id'
                    if ($age < 18) {
                        $set('guardian_id_visible', true);
                    } else {
                        $set('guardian_id_visible', false);
                    }
                }),
            Select::make('guardian_id')
                ->label('Responsável Legal')
                ->live()
                ->preload()
                ->searchable()
                ->relationship('guardian', 'name')
                ->createOptionForm(GuardianForm::form())
                ->visible(fn($get) => $get('guardian_id_visible') === true)
                ->required(fn($get) => $get('guardian_id_visible') === true),
            Document::make('cpf')
                ->cpf()
                ->label('CPF')
                ->validation(false),
            TextInput::make('rg')
                ->label('RG')
                ->maxLength(255),
            Radio::make('gender')
                ->label('Gênero')
                ->options([
                    'MASCULINO' => 'Masculino',
                    'FEMININO' => 'Feminino',
                ]),
            PhoneNumber::make('phone')
                ->label('Telefone'),
            FormFields::address(),
            FormFields::note(),
        ];

        if($hasSection) {
            return [
                Section::make('Dados do Aluno')
                    ->description(
                        fn(string $operation): string => $operation === 'create' || $operation === 'edit' ? 'Informe os campos solicitados' : ''
                    )
                    ->schema($formData)
                    ->columns(2)
            ];
        } else {
            return $formData;
        }
    }
}
