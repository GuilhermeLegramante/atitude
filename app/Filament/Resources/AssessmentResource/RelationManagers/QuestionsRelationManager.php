<?php

namespace App\Filament\Resources\AssessmentResource\RelationManagers;

use App\Filament\Forms\FormFields;
use App\Models\QuestionType;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class QuestionsRelationManager extends RelationManager
{
    protected static string $relationship = 'questions';

    protected static ?string $title = 'Questões';

    protected static ?string $label = 'Questão';

    protected static ?string $pluralLabel = 'Questões';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('question_type_id')
                    ->label('Tipo de Questão')
                    ->live()
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull()
                    ->relationship('questionType', 'type_name'),
                TextInput::make('question_text')
                    ->label('Enunciado da Questão')
                    ->required()
                    ->columnSpanFull()
                    ->maxLength(255),
                
               
                Repeater::make('alternatives')
                    ->label('Alternativas')
                    ->relationship('alternatives')
                    ->columnSpanFull()
                    ->visible(function (Get $get) {
                        return !is_null($get('question_type_id')) && QuestionType::find($get('question_type_id'))->type_name == 'Objetiva';
                    })
                    ->schema([
                        TextInput::make('alternative_text')
                            ->label('Texto')
                            ->required()
                            ->columnSpanFull()
                            ->maxLength(255),
                        Toggle::make('is_correct')
                            ->label('Alternativa Correta?')
                            ->inline(false)
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-check'),
                    ]),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('question_text')
            ->columns([
                TextColumn::make('questionType.type_name')
                    ->label('Tipo'),
                TextColumn::make('question_text')
                    ->label('Enunciado'),
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
