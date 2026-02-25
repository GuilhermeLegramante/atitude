<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LiveClassResource\Pages;
use App\Filament\Resources\LiveClassResource\RelationManagers;
use App\Models\LiveClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LiveClassResource extends Resource
{
    protected static ?string $model = LiveClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('language')
                    ->label('Idioma')
                    ->options([
                        'en' => 'Inglês',
                        'es' => 'Espanhol',
                    ])
                    ->required(),

                Forms\Components\Select::make('weekday')
                    ->label('Dia da Semana')
                    ->options([
                        'segunda' => 'Segunda-feira',
                        'terca' => 'Terça-feira',
                        'quarta' => 'Quarta-feira',
                        'quinta' => 'Quinta-feira',
                        'sexta' => 'Sexta-feira',
                        'sabado' => 'Sábado',
                    ])
                    ->required(),

                Forms\Components\TimePicker::make('time')
                    ->label('Horário')
                    ->required(),

                Forms\Components\TextInput::make('link')
                    ->label('Link da Aula')
                    ->url()
                    ->required(),

                Forms\Components\TextInput::make('description')
                    ->label('Descrição')
                    ->maxLength(255),

                Forms\Components\Toggle::make('active')
                    ->label('Ativo')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('language')
                    ->label('Idioma')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'en' ? 'Inglês' : 'Espanhol'),

                Tables\Columns\TextColumn::make('weekday')
                    ->label('Dia'),

                Tables\Columns\TextColumn::make('time')
                    ->label('Horário'),

                Tables\Columns\TextColumn::make('description')
                    ->label('Descrição')
                    ->limit(30),

                Tables\Columns\IconColumn::make('active')
                    ->boolean(),
            ])
            ->defaultSort('weekday')
            ->filters([
                Tables\Filters\SelectFilter::make('language')
                    ->options([
                        'en' => 'Inglês',
                        'es' => 'Espanhol',
                    ]),
            ]);
    }
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLiveClasses::route('/'),
            'create' => Pages\CreateLiveClass::route('/create'),
            'edit' => Pages\EditLiveClass::route('/{record}/edit'),
        ];
    }
}
