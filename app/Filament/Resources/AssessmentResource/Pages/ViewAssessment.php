<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Models\Alternative;
use App\Models\Answer;
use App\Models\QuestionType;
use App\Utils\AssessmentCalc;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\ViewField;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Livewire\WithFileUploads;

class ViewAssessment extends EditRecord
{
    use WithFileUploads;

    protected static string $resource = AssessmentResource::class;

    protected static string $view = 'pages.view-assessment';

    protected static ?string $breadcrumb = 'Visualizar';

    protected bool $hasAnswers = false;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\EditAction::make(),
        ];
    }

    public function getTitle(): string | Htmlable
    {
        return "Visualizar " . $this->getRecordTitle();
    }

    protected function getSaveFormAction(): Action
    {
        return Action::make('save')
            ->label('Enviar avaliação')
            ->submit('save')
            ->keyBindings(['mod+s']);
    }

    protected function authorizeAccess(): void
    {
        // Alterado o canEdit para canView, pois está herdando da EditRecord
        abort_unless(static::getResource()::canView($this->getRecord()), 403);
    }

    public function save(bool $shouldRedirect = true): void
    {
        $this->authorizeAccess();

        try {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeSave($data);

            $this->callHook('beforeSave');

            $this->saveAnswer($data);

            $this->callHook('afterSave');

            redirect()->route('filament.admin.pages.dashboard');
        } catch (Halt $exception) {
            return;
        }

        $this->rememberData();

        $this->getSavedNotification()?->send();
    }

    private function saveAnswer($data)
    {
        foreach ($data as $key => $value) {
            // Separando a string usando o underline
            $parts = explode('_', $key);

            // Definindo o question_id, baseado no primeiro número antes do underline
            $question_id = $parts[0];

            // Pegando o tipo da questão (objective, discursive, upload)
            $type = $parts[1];

            // Pegando o ID do usuário autenticado
            $user_id = auth()->user()->id;

            // Preparando o array de dados para a criação do modelo Answer
            $answerData = [
                'question_id' => $question_id,
                'user_id' => $user_id,
            ];

            // Dependendo do tipo, preenchendo os campos de forma diferente
            switch ($type) {
                case 'objective':
                    // Para tipo 'objective', pegamos o alternative_id
                    $answerData['alternative_id'] = $value;
                    break;

                case 'discursive':
                    // Para tipo 'discursive', pegamos o texto da resposta
                    $answerData['answer_text'] = $value; // Se for discursive, pode ser o texto da resposta
                    break;

                case 'upload':
                    // Para tipo 'upload', o file_path seria algum caminho relacionado ao arquivo
                    $answerData['file_path'] = $value; // Aqui você deve colocar a lógica para salvar o caminho do arquivo
                    break;

                default:
                    // Caso o tipo não seja reconhecido, pode-se ignorar ou adicionar uma lógica de erro
                    break;
            }

            // Salvando os dados no modelo Answer
            Answer::create($answerData);
        }
    }

    public function form(Form $form): Form
    {
        $assessment = $this->record;

        // Pegando todas as respostas do usuário relacionadas à avaliação
        $answers = $assessment
            ->questions()
            ->with(['answers' => function ($query) {
                $query->where('user_id', auth()->user()->id);
            }])
            ->get()
            ->pluck('answers')
            ->flatten();

        $checked = true;

        foreach ($answers as $answer) {
            if ($answer->cheched == false) {
                $checked = false;
            }
        }

        // Verifica se há respostas
        $hasAnswers = $answers->isNotEmpty();

        $this->hasAnswers = $hasAnswers;

        // Carregando tipos de perguntas para evitar múltiplos finds
        $questionTypes = QuestionType::all()->keyBy('id');

        return $form->schema([
            Section::make($this->record->lesson->class->course->name . '  >  ' .
                $this->record->lesson->class->name . '  >  ' .
                $this->record->lesson->title . '  >  ' .
                $this->record->name)
                ->schema(function () use ($answers, $hasAnswers, $questionTypes, $checked) {
                    $assessment = $this->record;
                    $questions = $assessment->questions;

                    $fieldSets = []; // Usando array ao invés de coleção

                    foreach ($questions as $key => $question) {
                        $fields = []; // Usando array ao invés de coleção
                        $questionType = $questionTypes[$question->question_type_id] ?? null;
                        $questionText = $question->question_text;

                        // Placeholder inicial para a pergunta
                        $fields[] = Placeholder::make($question->id)
                            ->columnSpanFull()
                            ->label($questionText);

                        if ($hasAnswers) {
                            $answerForQuestion = $answers->where('question_id', $question->id)->first();

                            if ($answerForQuestion) {
                                $fields = array_merge($fields, $this->getAnswerFields($question, $answerForQuestion, $questionType));
                            }
                        } else {
                            // Formulário padrão se não houver respostas
                            $fields = array_merge($fields, $this->getInputFields($question, $questionType));
                        }

                        // Agrupando os campos, já como array
                        $fieldSets[] = Fieldset::make('Questão ' . ($key + 1))
                            ->schema($fields); // Passando diretamente o array
                    }

                    if ($checked == false) {
                        $fieldSets[] = $this->getResult($answers);
                    }

                    return $fieldSets;
                }),
        ]);
    }

    private function getResult($answers): FieldSet
    {
        return Fieldset::make('Resultado da Avaliação')
            ->schema([
                Placeholder::make('result')
                    ->columnSpanFull()
                    ->content(AssessmentCalc::getScore($answers) . '% de acertos')
                    ->label('')
            ]);
    }

    private function getAnswerFields($question, $answerForQuestion, $questionType)
    {
        $fields = []; // Usando array ao invés de coleção

        $correctionText = 'A resposta está ERRADA';

        switch ($questionType->type_name) {
            case 'Objetiva':
                // Primeiramente, buscamos a alternativa que o usuário escolheu
                $selectedAlternative = Alternative::find($answerForQuestion->alternative_id);

                // Exibindo todas as alternativas, destacando a selecionada
                foreach ($question->alternatives as $key => $alternative) {
                    $text = $key + 1 . ' - ' . $alternative->alternative_text;

                    // Se for a alternativa que o usuário marcou, destacamos
                    if ($alternative->id == $selectedAlternative->id && $alternative->is_correct) {
                        // Atualizar a coluna is_correct do Model Answer
                        $answerForQuestion->is_correct = true;
                        $answerForQuestion->checked = true;
                        $answerForQuestion->save();

                        $correctionText = 'A resposta está CORRETA';
                    }

                    if ($alternative->is_correct) {
                        $text = '<strong>' . $text . '</strong>';
                    }

                    // Adicionando um Placeholder para cada alternativa
                    $fields[] = Placeholder::make($question->id . '_alternative_' . $alternative->id)
                        ->label('')
                        ->content(new HtmlString($text))
                        ->columnSpanFull(); // Exibe a alternativa
                }

                // Exibindo a alternativa marcada pelo usuário
                $fields[] = Placeholder::make($question->id . '_answer')
                    ->label('Sua Resposta: ' . $selectedAlternative->alternative_text)
                    ->columnSpanFull(); // Exibe a resposta do usuário

                // Exibindo a correção
                $fields[] = Placeholder::make($question->id . '_answer')
                    ->label('')
                    ->content($correctionText)
                    ->columnSpanFull(); // Exibe a resposta do usuário

                break;

            case 'Discursiva':
                $text = $answerForQuestion->answer_text;
                $fields[] = Placeholder::make($question->id . '_answer')
                    ->label('Sua Resposta: ' . $text)
                    ->columnSpanFull();

                $fields[] = $this->getFeedback($answerForQuestion->note, $fields);

                break;

            case 'Upload de Áudio':
                $audioPath = $answerForQuestion->file_path;
                if ($audioPath) {
                    $fields[] = ViewField::make($answerForQuestion->file_path)
                        ->label('Áudio enviado')
                        ->view('forms.audio-link', ['audioPath' => $audioPath]);
                } else {
                    $fields[] = Placeholder::make($question->id . '_audio')
                        ->columnSpanFull()
                        ->label('Nenhum áudio carregado.');
                }

                $fields[] = $this->getFeedback($answerForQuestion->note, $fields);

                break;
        }

        return $fields; // Retornando um array de campos
    }

    private function getFeedback($note, $fields): Placeholder
    {
        if (isset($note)) {
            return Placeholder::make('comment')
                ->label('Comentário do professor: ' . $note)
                ->columnSpanFull();
        } else {
            return Placeholder::make('comment')
                ->label('Questão ainda NÃO avaliada.')
                ->columnSpanFull();
        }
    }

    private function getInputFields($question, $questionType)
    {
        $fields = []; // Usando array ao invés de coleção

        switch ($questionType->type_name) {
            case 'Objetiva':
                $fields[] = Radio::make($question->id . "_objective")
                    ->label('Selecionar a resposta correta')
                    ->required()
                    ->validationMessages(['required' => 'Por favor, selecione a resposta correta para a questão.'])
                    ->options(function () use ($question) {
                        return $question->alternatives->pluck('alternative_text', 'id');
                    });
                break;

            case 'Discursiva':
                $fields[] = Textinput::make($question->id . "_discursive")
                    ->label('Resposta')
                    ->required()
                    ->validationMessages(['required' => 'Por favor, forneça uma resposta para a questão.'])
                    ->maxLength(65535)
                    ->columnSpanFull();
                break;

            case 'Upload de Áudio':
                $fields[] = FileUpload::make($question->id . "_upload")
                    ->label('Upload de Áudio')
                    ->previewable()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->acceptedFileTypes(['audio/mpeg', 'audio/wav', 'audio/ogg'])
                    ->rules(['file' => 'mimes:mp3,wav,ogg|max:10240']) // 10MB 
                    ->validationMessages(['required' => 'Por favor, envie um arquivo de áudio.'])
                    ->required()
                    ->directory('uploads/audio');
                break;
        }

        return $fields; // Retornando um array de campos
    }
}
