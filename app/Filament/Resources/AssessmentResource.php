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
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Grouping\Group;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AssessmentResource extends Resource
{
    protected static ?string $model = Assessment::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';

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
                Textarea::make('description')
                    ->label('Descrição')
                    ->rows(3)
                    ->maxLength(65535)
                    ->columnSpanFull(),
                FileUpload::make('audio_path')
                    ->label('Áudio')
                    ->disk('public') // ou outro disk configurado
                    ->directory('audios')
                    ->acceptedFileTypes(['audio/*'])
                    ->nullable(),
                FileUpload::make('image_path')
                    ->label('Imagem')
                    ->disk('public')
                    ->directory('images')
                    ->image()
                    ->maxSize(2048) // 2MB
                    ->nullable(),
                Toggle::make('visible')
                    ->label('Visível')
                    ->default(true)
                    ->helperText('Defina se esta avaliação estará visível ou não.'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            // ->modifyQueryUsing(function (Builder $query) {
            //     // Pega o usuário logado
            //     $user = auth()->user();

            //     // Pega o estudante vinculado ao usuário logado
            //     $student = $user->student;

            //     if ($student) {
            //         $query->whereHas('lesson.class.students', function ($q) use ($student) {
            //             $q->where('students.id', $student->id);
            //         });
            //     }

            //     // Ordena pelo name do mais antigo para o mais novo
            //     return $query->orderBy('name', 'asc');
            // })
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
            ->groups([
                Group::make('lesson.class.course.name')
                    ->label('Curso')
                    ->collapsible(),
            ])
            // define o agrupamento padrão (pode receber string ou Group::make(...))
            ->defaultGroup('lesson.class.course.name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn($record) => $record->visible),
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
