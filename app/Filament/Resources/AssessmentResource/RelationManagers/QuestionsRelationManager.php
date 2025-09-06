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

                // Upload de imagem (condicional)
                FileUpload::make('image_path')
                    ->label('Imagem da Questão')
                    ->image()
                    ->directory('questions/images')
                    ->maxSize(10240) // 10 MB
                    ->openable()
                    ->downloadable()
                    ->previewable()
                    ->columnSpanFull()
                    ->visible(function (Get $get) {
                        $typeId = $get('question_type_id');
                        $type = $typeId ? QuestionType::find($typeId) : null;
                        return $type && $type->type_name === 'Upload de Imagem';
                    }),

                // Upload de áudio (condicional)
                FileUpload::make('audio_path')
                    ->label('Áudio da Questão')
                    ->directory('questions/audio')
                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg'])
                    ->maxSize(10240) // 10 MB
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->visible(function (Get $get) {
                        $typeId = $get('question_type_id');
                        $type = $typeId ? QuestionType::find($typeId) : null;
                        return $type && $type->type_name === 'Upload de Áudio';
                    }),

                // Upload de PDF (condicional)
                // FileUpload::make('pdf_path')
                //     ->label('PDF da Questão')
                //     ->directory('questions/pdf')
                //     ->acceptedFileTypes(['application/pdf'])
                //     ->maxSize(20480) // 20 MB
                //     ->openable()
                //     ->downloadable()
                //     ->columnSpanFull()
                //     ->visible(function (Get $get) {
                //         $typeId = $get('question_type_id');
                //         $type = $typeId ? QuestionType::find($typeId) : null;
                //         return $type && $type->type_name === 'Upload de PDF';
                //     }),

                // Gabarito → só Discursiva
                Textarea::make('gabarito')
                    ->label('Gabarito (apenas para questões discursivas)')
                    ->maxLength(65535)
                    ->columnSpanFull()
                    ->visible(function (Get $get) {
                        $typeId = $get('question_type_id');
                        $type = $typeId ? QuestionType::find($typeId) : null;
                        return $type && $type->type_name === 'Discursiva';
                    }),

                // Alternativas → só Objetiva
                Repeater::make('alternatives')
                    ->label('Alternativas')
                    ->relationship('alternatives')
                    ->columnSpanFull()
                    ->visible(function (Get $get) {
                        $typeId = $get('question_type_id');
                        $type = $typeId ? QuestionType::find($typeId) : null;
                        return $type && $type->type_name === 'Objetiva';
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
