<?php

namespace App\Filament\Resources\AssessmentResource\Pages;

use App\Filament\Resources\AssessmentResource;
use App\Mail\AnswerSent;
use App\Models\Alternative;
use App\Models\Answer;
use App\Models\Question;
use App\Models\QuestionType;
use App\Models\Student;
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
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Filament\Support\Exceptions\Halt;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Columns\ViewColumn;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\HtmlString;
use Livewire\WithFileUploads;
use Filament\Forms\Components\HtmlContent;

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

            // Se não for numérico, ignora este campo
            if (!is_numeric($question_id)) {
                continue;
            }
            $question_id = (int) $question_id;

            // Pegando o tipo da questão (objective, discursive, upload)
            $type = $parts[1] ?? null;

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
                    $answerData['alternative_id'] = $value;
                    break;

                case 'discursive':
                    $answerData['answer_text'] = $value;
                    break;

                case 'upload':
                    // Se for upload, $value é um objeto UploadedFile ou o caminho gerado pelo FileUpload
                    if (is_string($value)) {
                        $answerData['file_path'] = $value;
                    } elseif (is_object($value) && method_exists($value, 'store')) {
                        // Para uploads diretos (se estiver enviando o arquivo)
                        $answerData['file_path'] = $value->store('uploads', 'public');
                    }
                    break;

                default:
                    // Ignora campos que não correspondem a tipos conhecidos
                    continue 2;
            }

            // Salva a resposta
            Answer::create($answerData);
        }

        // Envia notificação para o professor
        $user = auth()->user();

        if (isset($user->student)) {
            $student = Student::find($user->student->id ?? $user->student->name);

            // Pega a última questão salva para enviar detalhes da atividade
            $lastAnswer = Answer::where('user_id', $user->id)
                ->latest()
                ->first();

            if ($lastAnswer) {
                $question = Question::find($lastAnswer->question_id);
                $assessment = $question->assessment ?? null;

                if ($assessment) {
                    $teacher = $assessment->lesson->class->course->user->name ?? '';
                    $course = $assessment->lesson->class->course->name ?? '';
                    $class = $assessment->lesson->class->name ?? '';
                    $activity = $assessment->lesson->title ?? '';

                    Mail::to($user)->queue(new AnswerSent($teacher, $student->name, $course, $class, $activity));

                    Notification::make()
                        ->title('Atividade enviada')
                        ->body($course . ' - ' . $class . ' - ' . $activity . ' - ' . $student)
                        ->sendToDatabase($user, isEventDispatched: true);
                }
            }
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

                    $fieldSets = [];

                    // =========================
                    // Conteúdo multimídia da atividade
                    // =========================
                    if ($assessment->image_path) {
                        $fieldSets[] = ViewField::make('atividade_imagem')
                            ->label('Imagem da Atividade')
                            ->view('components.assessment-image', [
                                'imagePath' => $assessment->image_path,
                            ])
                            ->columnSpanFull();
                    }

                    if ($assessment->audio_path) {
                        $fieldSets[] = ViewField::make('atividade_audio')
                            ->label('Áudio da Atividade')
                            ->view('components.assessment-audio', [
                                'audioPath' => $assessment->audio_path,
                            ])
                            ->columnSpanFull();
                    }

                    // =========================
                    // Loop das questões
                    // =========================
                    foreach ($questions as $key => $question) {
                        $fields = [];
                        $questionType = $questionTypes[$question->question_type_id] ?? null;
                        $questionText = $question->question_text;

                        // Placeholder inicial para a pergunta
                        $fields[] = Placeholder::make($question->id)
                            ->columnSpanFull()
                            ->label($questionText);

                        // 🔽 NOVO: exibir imagem ou áudio da questão se existir
                        if ($question->image_path) {
                            $fields[] = ViewField::make('question_' . $question->id . '_image')
                                ->label('')
                                ->view('components.question-image', [
                                    'imagePath' => $question->image_path,
                                ])
                                ->columnSpanFull();
                        }

                        if ($question->audio_path) {
                            $fields[] = ViewField::make('question_' . $question->id . '_audio')
                                ->label('')
                                ->view('components.question-audio', [
                                    'audioPath' => $question->audio_path,
                                ])
                                ->columnSpanFull();
                        }

                        if ($question->pdf_path) {
                            $fields[] = ViewField::make('question_' . $question->id . '_pdf')
                                ->label('')
                                ->view('components.question-pdf', [
                                    'pdfPath' => $question->pdf_path,
                                ])
                                ->columnSpanFull();
                        }

                        if ($hasAnswers) {
                            $answerForQuestion = $answers->where('question_id', $question->id)->first();

                            if ($answerForQuestion) {
                                $fields = array_merge($fields, $this->getAnswerFields($question, $answerForQuestion, $questionType));
                            }
                        } else {
                            // Formulário padrão se não houver respostas
                            $fields = array_merge($fields, $this->getInputFields($question, $questionType));
                        }

                        // Agrupando os campos
                        $fieldSets[] = Fieldset::make('Questão ' . ($key + 1))
                            ->schema($fields);
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
                $selectedAlternative = Alternative::find($answerForQuestion->alternative_id);

                foreach ($question->alternatives as $key => $alternative) {
                    $text = $key + 1 . ' - ' . $alternative->alternative_text;

                    if ($alternative->id == $selectedAlternative->id && $alternative->is_correct) {
                        $answerForQuestion->is_correct = true;
                        $answerForQuestion->checked = true;
                        $answerForQuestion->save();
                        $correctionText = 'A resposta está CORRETA';
                    }

                    if ($alternative->is_correct) {
                        $text = '<strong>' . $text . '</strong>';
                    }

                    $fields[] = Placeholder::make($question->id . '_alternative_' . $alternative->id)
                        ->label('')
                        ->content(new HtmlString($text))
                        ->columnSpanFull();
                }

                $fields[] = Placeholder::make($question->id . '_answer')
                    ->label('Sua Resposta: ' . $selectedAlternative->alternative_text)
                    ->columnSpanFull();

                $fields[] = Placeholder::make($question->id . '_answer')
                    ->label('')
                    ->content($correctionText)
                    ->columnSpanFull();
                break;

            case 'Discursiva':
                $fields[] = Placeholder::make($question->id . '_answer')
                    ->label('Sua Resposta:')
                    ->content($answerForQuestion->answer_text)
                    ->columnSpanFull();

                if ($question->gabarito) {
                    $fields[] = Placeholder::make($question->id . '_gabarito')
                        ->label('Gabarito do Professor:')
                        ->content($question->gabarito)
                        ->columnSpanFull();
                }

                $fields[] = $this->getFeedback($answerForQuestion->note, $fields);
                break;

            case 'Upload de Áudio':
                $audioPath = $answerForQuestion->file_path;
                if ($audioPath) {
                    $fields[] = ViewField::make('audio_' . $question->id)
                        ->label('Áudio enviado')
                        ->view('forms.audio-link', ['audioPath' => $audioPath]);
                } else {
                    $fields[] = Placeholder::make($question->id . '_audio')
                        ->columnSpanFull()
                        ->label('Nenhum áudio carregado.');
                }
                $fields[] = $this->getFeedback($answerForQuestion->note, $fields);
                break;

            case 'Upload de Vídeo':
                $videoPath = $answerForQuestion->file_path;
                if ($videoPath) {
                    $fields[] = ViewField::make('video_' . $question->id)
                        ->label('Vídeo enviado')
                        ->view('forms.video-link', ['videoPath' => $videoPath]);
                } else {
                    $fields[] = Placeholder::make($question->id . '_video')
                        ->columnSpanFull()
                        ->label('Nenhum vídeo carregado.');
                }
                $fields[] = $this->getFeedback($answerForQuestion->note, $fields);
                break;

            case 'Upload de Imagem':
                $imagePath = $answerForQuestion->file_path;

                if ($imagePath) {
                    $url = asset('storage/' . $imagePath); // gera URL pública

                    $fields[] = ViewField::make('image_' . $question->id)
                        ->label('Imagem enviada')
                        ->view('forms.image-link', ['imagePath' => $url])
                        ->columnSpanFull();
                } else {
                    $fields[] = Placeholder::make('image_' . $question->id)
                        ->label('Nenhuma imagem carregada')
                        ->columnSpanFull();
                }
                break;

            case 'Upload de PDF':
                $filePath = $answerForQuestion->file_path;

                if ($filePath) {
                    $url = asset('storage/' . $filePath); // gera URL completa

                    $fields[] = Placeholder::make('file_' . $question->id)
                        ->label('PDF enviado')
                        ->content(new HtmlString('<a href="' . $url . '" target="_blank">Abrir PDF</a>'))
                        ->columnSpanFull();
                } else {
                    $fields[] = Placeholder::make('file_' . $question->id)
                        ->label('Nenhum PDF carregado')
                        ->columnSpanFull();
                }
                break;
                $fields[] = $this->getFeedback($answerForQuestion->note, $fields);
                break;
        }

        return $fields;
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
                $fields[] = TextInput::make($question->id . "_discursive")
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

            case 'Upload de Vídeo':
                $fields[] = FileUpload::make($question->id . "_upload")
                    ->label('Upload de Vídeo')
                    ->previewable()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->acceptedFileTypes(['video/mp4', 'video/avi', 'video/mov', 'video/webm', 'video/mkv'])
                    ->rules(['file' => 'mimes:mp4,avi,mov,webm,mkv|max:51200']) // 50MB
                    ->validationMessages(['required' => 'Por favor, envie um arquivo de vídeo.'])
                    ->required()
                    ->directory('uploads/video');
                break;

            case 'Upload de Imagem':
                $fields[] = FileUpload::make($question->id . "_upload")
                    ->label('Upload de Imagem')
                    ->image()
                    ->previewable()
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/jpg', 'image/gif'])
                    ->rules(['file' => 'mimes:jpg,jpeg,png,gif|max:10240']) // 10MB
                    ->validationMessages(['required' => 'Por favor, envie uma imagem.'])
                    ->required()
                    ->directory('uploads/images');
                break;

            case 'Upload de PDF':
                $fields[] = FileUpload::make($question->id . "_upload")
                    ->label('Upload de PDF')
                    ->previewable(false) // PDF geralmente não tem preview direto
                    ->openable()
                    ->downloadable()
                    ->columnSpanFull()
                    ->acceptedFileTypes(['application/pdf'])
                    ->rules(['file' => 'max:10240']) // 10MB
                    ->validationMessages(['required' => 'Por favor, envie um arquivo PDF.'])
                    ->required()
                    ->directory('uploads/pdf');
                break;
        }

        return $fields; // Retornando um array de campos
    }
}
