<?php

namespace App\Filament\Resources;

use App\Filament\Forms\FormFields;
use App\Filament\Resources\FileResource\Pages;
use App\Filament\Resources\FileResource\RelationManagers;
use App\Filament\Tables\TableColumns;
use App\Models\File;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FileResource extends Resource
{
    protected static ?string $model = File::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'Material';

    protected static ?string $pluralModelLabel = 'Materiais';

    protected static ?string $navigationGroup = 'Ensino';

    protected static ?string $slug = 'materiais';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FormFields::name(),
                Textarea::make('description')
                    ->label('Descrição')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                FileUpload::make('url')
                    ->label('Arquivo')
                    ->previewable()
                    ->openable()
                    ->maxSize(102400)
                    ->downloadable()
                    ->columnSpanFull()
                    ->imageEditor(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    // ->description(fn(File $record): string => $record->description)
                    ->label('Nome')
                    ->searchable(),
                TextColumn::make('description')
                    ->label('Descrição')
                    ->wrap()
                    ->searchable(),
                ViewColumn::make('url')
                    ->label('Arquivo')
                    ->view('tables.columns.file-link'),
                TableColumns::createdAt(),
                TableColumns::updatedAt(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageFiles::route('/'),
        ];
    }
}
