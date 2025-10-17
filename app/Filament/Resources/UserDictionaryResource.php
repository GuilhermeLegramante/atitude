<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserDictionaryResource\Pages;
use App\Models\UserDictionary;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Illuminate\Support\Facades\Auth;

class UserDictionaryResource extends Resource
{
    protected static ?string $model = UserDictionary::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';
    protected static ?string $navigationLabel = 'Meu Dicionário';
    protected static ?string $pluralLabel = 'Palavras';
    protected static ?string $modelLabel = 'Palavra';

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('word')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('translation')
                    ->required()
                    ->maxLength(255),
                // user_id será preenchido automaticamente
            ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('word')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('translation')->sortable()->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserDictionaries::route('/'),
            'create' => Pages\CreateUserDictionary::route('/create'),
            'edit' => Pages\EditUserDictionary::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return (string) UserDictionary::where('user_id', Auth::id())->count();
    }
}
