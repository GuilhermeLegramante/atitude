<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ExperienceResource\Pages;
use App\Filament\Resources\ExperienceResource\RelationManagers;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use LevelUp\Experience\Models\Experience;

class ExperienceResource extends Resource
{
    protected static ?string $model = Experience::class;

    protected static ?string $navigationIcon = 'heroicon-o-list-bullet';

    protected static ?string $recordTitleAttribute = 'user.name';

    protected static ?string $modelLabel = 'ranking XP';

    protected static ?string $pluralModelLabel = 'ranking XP';

    protected static ?string $navigationGroup = 'Gamificação';

    protected static ?string $slug = 'ranking-xp';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(Experience::orderBy('experience_points', 'desc'))  // Ordena pela coluna 'pontos' de forma decrescente
            ->columns([
                TextColumn::make('ranking')
                    ->label('Colocação')
                    ->alignCenter()
                    ->sortable(false)
                    ->getStateUsing(function ($record, $column) use ($table) {
                        // Obtemos todas as experiências ordenadas
                        $allExperiences = $table->getQuery()->get();
                        // Encontramos a posição do registro na lista
                        $position = $allExperiences->search(fn($item) => $item->getKey() === $record->getKey());
                        return $position + 1 . '°'; // A posição é o índice na coleção, mais 1 para começar do 1
                    }),
                TextColumn::make('user.name')
                    ->label('Nome')
                    ->sortable(false)
                    ->searchable(),
                TextColumn::make('experience_points')
                    ->label('Pontos')
                    ->alignCenter()
                    ->sortable(false)
                    ->searchable(),
            ])
            // ->defaultSort('experience_points', 'desc')
            ->filters([
                //
            ])
            ->actions([
                // Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    // Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageExperiences::route('/'),
        ];
    }


    public static function canCreate(): bool
    {
        return false;
    }
}
