<?php

namespace App\Filament\Resources\LessonResource\RelationManagers;

use App\Filament\Forms\FormFields;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MaterialsRelationManager extends RelationManager
{
    protected static string $relationship = 'materials';

    protected static ?string $title = 'Materiais';

    protected static ?string $label = 'Material';

    protected static ?string $pluralLabel = 'Materiais';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FormFields::name(),
                FormFields::description(false),
                FileUpload::make('url')
                    ->label('Arquivo')
                    ->previewable()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->imageEditor(),
                FormFields::note()
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->label('Nome')
                    ->searchable(),
                ViewColumn::make('url')
                    ->label('Arquivo')
                    ->view('tables.columns.file-link')
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
