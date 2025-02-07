<?php

namespace App\Filament\Resources\CourseResource\RelationManagers;

use App\Filament\Forms\FormFields;
use App\Filament\Tables\ClassTable;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'classes';

    protected static ?string $title = 'Turmas';

    protected static ?string $label = 'Turma';

    protected static ?string $pluralLabel = 'Turmas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                FormFields::name(),
                FormFields::note(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(ClassTable::table())
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
