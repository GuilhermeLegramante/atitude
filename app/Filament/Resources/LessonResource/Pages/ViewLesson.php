<?php

namespace App\Filament\Resources\LessonResource\Pages;

use App\Filament\Resources\LessonResource;
use App\Models\Lesson;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\DB;

class ViewLesson extends ViewRecord
{
    protected static string $resource = LessonResource::class;

    public $watched;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $watched = DB::table('lesson_student')
            ->where('lesson_id', $data['id'])
            ->value('watched');

        $this->watched = $watched ? true : false;

        return $data;
    }

    public function updatedWatched($value)
    {
        $student = auth()->user()->student;  // Estudante logado
        $lesson = $this->getRecord();  // A lição que você está associando ao estudante
        $state = $value;  // Estado de 'watched' (true ou false)

        // Realizando o insert ou update na tabela pivô 'lesson_student'
        $op =  DB::table('lesson_student')
            ->updateOrInsert(
                [
                    'student_id' => $student->id,  // Chave estrangeira do estudante
                    'lesson_id' => $lesson->id,    // Chave estrangeira da lição
                ],
                [
                    'watched' => $state,  // Campo da tabela pivô que você quer atualizar
                    'created_at' => now(),  // Você também pode adicionar ou omitir 'created_at'
                    'updated_at' => now()   // Se necessário
                ]
            );
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
