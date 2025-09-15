<?php

namespace App\Filament\Resources;

use App\Filament\Forms\ClassForm;
use App\Filament\Resources\ClassModelResource\Pages;
use App\Filament\Resources\ClassModelResource\RelationManagers;
use App\Filament\Tables\ClassTable;
use App\Models\ClassModel;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClassModelResource extends Resource
{
    protected static ?string $model = ClassModel::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'turma';

    protected static ?string $pluralModelLabel = 'turmas';

    protected static ?string $navigationGroup = 'Ensino';

    protected static ?string $slug = 'turma';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(ClassForm::form());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(ClassTable::table())
            ->filters([
                //
            ])
            ->groups([
                Group::make('course.name')
                    ->label('Curso')
                    ->collapsible(),
            ])
            // define o agrupamento padrão (pode receber string ou Group::make(...))
            ->defaultGroup('course.name')
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeCells)
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\StudentsRelationManager::class,
            RelationManagers\LessonsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListClassModels::route('/'),
            'create' => Pages\CreateClassModel::route('/criar'),
            'edit' => Pages\EditClassModel::route('/{record}/editar'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}
