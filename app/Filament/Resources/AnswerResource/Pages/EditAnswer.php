<?php

namespace App\Filament\Resources\AnswerResource\Pages;

use App\Filament\Resources\AnswerResource;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\Support\Htmlable;

class EditAnswer extends EditRecord
{
    protected static string $resource = AnswerResource::class;

    protected static ?string $breadcrumb = 'Correção da Questão';

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return "Correção da Questão";
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make(
                    $this->record->question->assessment->lesson->class->course->name . '  >  ' .
                        $this->record->question->assessment->lesson->class->name . '  >  ' .
                        $this->record->question->assessment->lesson->title . '  >  ' .
                        $this->record->question->assessment->name
                )
                    ->description('Aluno: ' . $this->record->user->name)
                    ->schema([
                        Placeholder::make('question')
                            ->label('Enunciado da questão')
                            ->content($this->record->question->question_text)
                            ->columnSpanFull(),
                        Textarea::make('answer_text')
                            ->label('Resposta do Aluno')
                            ->disabled()
                            ->maxLength(65535)
                            ->visible($this->record->question->questionType->type_name == "Discursiva")
                            ->columnSpanFull(),
                        FileUpload::make('file_path')
                            ->label('Áudio enviado')
                            ->disabled()
                            ->previewable()
                            ->openable()
                            ->downloadable()
                            ->visible($this->record->question->questionType->type_name == "Upload de Áudio")
                            ->columnSpanFull(),
                        Textarea::make('note')
                            ->label('Comentário do Professor')
                            ->maxLength(65535)
                            ->columnSpanFull(),
                        Toggle::make('is_correct')
                            ->label('Está correta?')
                            ->inline(false)
                            ->onIcon('heroicon-o-check')
                            ->offIcon('heroicon-o-check'),
                    ])
                    ->columns(2)

            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if ($data["is_correct"]) {
            $data['checked'] = true;
        }

        return $data;
    }
}
