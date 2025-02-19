<?php

namespace App\Filament\Resources;

use App\Filament\Forms\ClassForm;
use App\Filament\Forms\FormFields;
use App\Filament\Forms\LessonForm;
use App\Filament\Resources\AssessmentResource\Pages;
use App\Filament\Resources\AssessmentResource\RelationManagers;
use App\Models\Assessment;
use App\Models\ClassModel;
use App\Models\Lesson;
use Filament\Forms;
use Filament\Forms\Components\Select;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $modelLabel = 'atividade';

    protected static ?string $pluralModelLabel = 'atividades';

    protected static ?string $navigationGroup = 'Ensino';

    protected static ?string $slug = 'atividade';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('lesson_id')
                    ->label('Aula')
                    ->live()
                    ->preload()
                    ->searchable()
                    ->required()
                    ->columnSpanFull()
                    ->relationship('lesson', 'title')
                    ->getOptionLabelFromRecordUsing(fn(Lesson $record) => "{$record->title} - {$record->class->name} - {$record->class->course->name}")
                    ->createOptionForm(LessonForm::form()),
                FormFields::name(),
                FormFields::description(false),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(function (Builder $query) {
                // Pega o usuário logado
                $user = auth()->user();

                // Pega o estudante vinculado ao usuário logado
                $student = $user->student;

                if ($student) {
                    // Modifica a consulta para filtrar as avaliações do estudante
                    return $query->whereHas('lesson.class.students', function ($q) use ($student) {
                        $q->where('students.id', $student->id);
                    });
                } else {
                    return $query;
                }
            })
            ->columns([
                TextColumn::make('name')
                    ->label('Título')
                    ->searchable(),
                TextColumn::make('lesson.title')
                    ->label('Aula')
                    ->sortable(),
                TextColumn::make('lesson.class.name')
                    ->label('Turma')
                    ->sortable(),
                TextColumn::make('lesson.class.course.name')
                    ->label('Curso')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
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
            RelationManagers\QuestionsRelationManager::class,
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAssessments::route('/'),
            'create' => Pages\CreateAssessment::route('/criar'),
            'edit' => Pages\EditAssessment::route('/{record}/editar'),
            'view' => Pages\ViewAssessment::route('/{record}'),
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

        // Conta o número de avaliações associadas ao estudante
        $assessmentCount = $student->classes()
            ->with('lessons.assessments')  // Eager load para evitar N+1 queries
            ->get()
            ->flatMap(function ($classModel) {
                return $classModel->lessons->flatMap(function ($lesson) {
                    return $lesson->assessments;
                });
            })
            ->count();

        // Retorna o número de avaliações, ou null se não houver avaliações
        return $assessmentCount > 0 ? (string) $assessmentCount : null;
    }
}
