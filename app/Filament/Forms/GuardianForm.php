<?php

namespace App\Filament\Forms;

use Carbon\Carbon;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Facades\Hash;
use Leandrocfe\FilamentPtbrFormFields\Document;
use Leandrocfe\FilamentPtbrFormFields\PhoneNumber;

class GuardianForm
{
    public static function form(): array
    {
        $minAgeDate = Carbon::now()->subYears(18);

        return [
            FormFields::name(),
            FormFields::email(),
            FileUpload::make('photo')
                ->label('Foto')
                ->avatar()
                ->columnSpanFull()
                ->previewable()
                ->openable()
                ->downloadable()
                ->imageEditor(),
            TextInput::make('relationship')
                ->label('Grau de parentesco (pai, mãe, tutor legal, etc.)')
                ->required()
                ->maxLength(255),
            DatePicker::make('birth_date')
                ->maxDate($minAgeDate) // Adiciona a data mínima (18 anos atrás)
                ->label('Data de Nascimento')
                ->required()
                ->rules('before_or_equal:' . now()->toDateString()),
            Document::make('cpf')
                ->label('CPF ou CNPJ')
                ->validation(false)
                ->dynamic(),
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
    }
}
