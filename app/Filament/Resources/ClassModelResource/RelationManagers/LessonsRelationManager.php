<?php

namespace App\Filament\Resources\ClassModelResource\RelationManagers;

use App\Filament\Forms\FormFields;
use App\Filament\Tables\LessonTable;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonsRelationManager extends RelationManager
{
    protected static string $relationship = 'lessons';

    protected static ?string $title = 'Aulas';

    protected static ?string $label = 'Aula';

    protected static ?string $pluralLabel = 'Aulas';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label('Título')
                    ->required()
                    ->maxLength(255),
                FormFields::description(),
                TextInput::make('video_link')
                    ->label('Link do Vídeo')
                    ->url()
                    ->maxLength(255),
                FormFields::note(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns(LessonTable::table())
            ->filters([
                //
            ])
            ->groups([
                Group::make('class.name')
                    ->label('Turma')
                    ->collapsible(),
                Group::make('class.course.name')
                    ->label('Curso')
                    ->collapsible(),
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ]),
            ], position: ActionsPosition::BeforeCells)
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
