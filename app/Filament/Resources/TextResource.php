<?php
// app/Filament/Resources/TextResource.php

namespace App\Filament\Resources;

use App\Filament\Resources\TextResource\Pages;
use App\Models\Text;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;

class TextResource extends Resource
{
    protected static ?string $model = Text::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationLabel = 'Textos';

    protected static ?string $modelLabel = 'texto';

    protected static ?string $pluralModelLabel = 'textos';

    protected static ?string $navigationGroup = 'Ensino';

    protected static ?string $slug = 'texto';


    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('title')
                    ->columnSpanFull()
                    ->label('Título')->required(),
                Forms\Components\Textarea::make('content')
                    ->columnSpanFull()
                    ->label('Texto')->required()->rows(20),
                Forms\Components\Select::make('language')
                    ->label('Idioma')
                    ->options(['en' => 'Inglês', 'es' => 'Espanhol'])
                    ->required(),
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title')
                    ->label('Título')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('language')
                    ->label('Idioma')
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'en' => 'Inglês',
                            'es' => 'Espanhol',
                            default => $state,
                        };
                    }),
                Tables\Columns\TextColumn::make('author.name')->label('Professor'),
            ])
            ->filters([]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTexts::route('/'),
            'create' => Pages\CreateText::route('/criar'),
            'edit' => Pages\EditText::route('/{record}/editar'),
        ];
    }
}
