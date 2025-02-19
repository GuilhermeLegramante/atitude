<?php

namespace App\Filament\Resources;

use App\Filament\Forms\LessonForm;
use App\Filament\Resources\LessonResource\Pages;
use App\Filament\Resources\LessonResource\RelationManagers;
use App\Filament\Tables\LessonTable;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Support\Enums\FontWeight;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Enums\ActionsPosition;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Hugomyb\FilamentMediaAction\Actions\MediaAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LessonResource extends Resource
{
    protected static ?string $model = Lesson::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'aula';

    protected static ?string $pluralModelLabel = 'aulas';

    protected static ?string $navigationGroup = 'Ensino';

    protected static ?string $slug = 'aula';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(LessonForm::form())
            ->columns(['sm' => 4, 'lg' => null]);;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function ($query) {
                // Obtém o usuário logado
                $user = auth()->user();

                // Obtém o estudante associado ao usuário logado
                $student = $user->student;

                if ($student) {
                    // Filtra as aulas relacionadas às turmas do estudante logado
                    return $query->whereHas('class.students', function ($q) use ($student) {
                        $q->where('students.id', $student->id);
                    });
                } else {
                    return $query;
                }
            })
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
                    Tables\Actions\ViewAction::make(),
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
            RelationManagers\MaterialsRelationManager::class,
            // RelationManagers\AssessmentsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLessons::route('/'),
            'create' => Pages\CreateLesson::route('/criar'),
            'view' => Pages\ViewLesson::route('/{record}'),
            'edit' => Pages\EditLesson::route('/{record}/editar'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        // Obtém o usuário logado
        $user = auth()->user();

        // Obtém o estudante vinculado ao usuário logado
        $student = $user->student;

        if (!$student) {
            return static::getModel()::count();
        }

        // Conta o número de aulas associadas ao estudante (considerando suas turmas)
        $lessonCount = $student->classes()
            ->with('lessons')  // Carrega as aulas associadas a cada turma
            ->get()
            ->flatMap(function ($classModel) {
                return $classModel->lessons;
            })
            ->count();

        // Retorna o número de aulas, ou null se não houver aulas
        return $lessonCount > 0 ? (string) $lessonCount : null;
    }
}
