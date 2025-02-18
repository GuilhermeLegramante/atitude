<?php

namespace App\Filament\Resources;

use App\Filament\Resources\AnswerResource\Pages;
use App\Filament\Resources\AnswerResource\RelationManagers;
use App\Filament\Tables\TableColumns;
use App\Models\Answer;
use Filament\Forms;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AnswerResource extends Resource
{
    protected static ?string $model = Answer::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'id';

    protected static ?string $modelLabel = 'resposta enviadas';

    protected static ?string $pluralModelLabel = 'respostas enviadas';

    protected static ?string $navigationGroup = 'Ensino';

    protected static ?string $slug = 'resposta-enviada';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('alternative_id')
                    ->numeric(),
                Forms\Components\Textarea::make('answer_text')
                    ->maxLength(65535)
                    ->columnSpanFull(),
                Forms\Components\TextInput::make('file_path')
                    ->maxLength(255),
                Forms\Components\Textarea::make('note')
                    ->maxLength(65535)
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Answer::query()->whereNull('alternative_id')->orderBy('created_at', 'desc')) // Não exibe questões objetivas
            ->columns([
                Tables\Columns\TextColumn::make('question.question_text')
                    ->label('Questão')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('Aluno')
                    ->sortable()
                    ->searchable(),
                IconColumn::make('checked')
                    ->label('Está corrigida?')
                    ->alignCenter()
                    ->toggleable(isToggledHiddenByDefault: false)
                    ->boolean(),
                TableColumns::createdAt(),
                TableColumns::updatedAt(),
            ])
            ->defaultGroup('question.assessment.name')
            ->filters([
                TernaryFilter::make('checked')
                    ->label('Está corrigida?')
                    ->default(0),
            ])
            ->groups([
                Group::make('question.assessment.name')
                    ->label('Atividade')
                    ->collapsible(),
                Group::make('user.name')
                    ->label('Aluno')
                    ->collapsible(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAnswers::route('/'),
            // 'create' => Pages\CreateAnswer::route('/create'),
            'edit' => Pages\EditAnswer::route('/{record}/corrigir'),
        ];
    }
}
