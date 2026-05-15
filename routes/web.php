<?php

use App\Http\Controllers\AssessmentController;
use App\Http\Controllers\DictionaryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\TextController;
use App\Mail\FirstEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Http;

use Datlechin\GoogleTranslate\Facades\GoogleTranslate;
use App\Http\Controllers\TranslatorController;
use App\Models\ClassModel;
use App\Models\Course;
use App\Models\Settings;
use App\Models\User;

Livewire::setScriptRoute(function ($handle) {
    return Route::get('/atitude/public/livewire/livewire.js', $handle);
});

Livewire::setUpdateRoute(function ($handle) {
    return Route::post('/atitude/public/livewire/update', $handle);
});

/**
 * Ao trocar a senha do usuário, o Laravel exige um novo login.
 * Para isso, é necessário informar a rota de login
 */
Route::get('/login', function () {
    return redirect(route('filament.admin.auth.login'));
})->name('login');

Route::get('/', function () {
    return redirect(route('filament.admin.pages.dashboard'));
});

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

Route::get('/cadastro', [HomeController::class, 'register'])
    ->name('register');

Route::get('/payments/{payment}/receipt', function (Payment $payment) {
    $pdf = Pdf::loadView('payments.receipt', compact('payment'));

    // Forçar download:
    // return $pdf->download('recibo_pagamento_'.$payment->id.'.pdf');

    // Ou abrir direto no navegador:
    return $pdf->stream('recibo_pagamento_' . $payment->id . '.pdf');
})->name('payments.receipt');


Route::get('/aulas/{lesson}', [LessonController::class, 'show'])
    ->middleware('auth')
    ->name('lessons.show');

Route::post('/lessons/{lesson}/toggle-watched', [LessonController::class, 'toggleWatched'])
    ->middleware('auth')
    ->name('lessons.toggleWatched');

Route::get('/assessments/{assessment}/modal', [AssessmentController::class, 'modal'])->name('assessments.modal');
Route::post('/assessments/{assessment}/submit', [AssessmentController::class, 'submit'])->name('assessments.submit');

Route::get('/assessments/{assessment}/answers', [AssessmentController::class, 'userAnswers']);

Route::get('/tradutor', [TranslatorController::class, 'index'])->name('translator.index');
Route::post('/tradutor', [TranslatorController::class, 'translate'])->name('translator.translate');
Route::post('/translator/ajax-translate', [TranslatorController::class, 'ajaxTranslate'])->name('translator.ajaxTranslate');


Route::middleware(['auth'])->group(function () {
    Route::get('/textos', [TextController::class, 'index'])->name('texts.index');
    Route::get('/textos/{text}', [TextController::class, 'show'])->name('texts.show');
    Route::post('/dictionary/save', [TextController::class, 'saveWord'])->name('dictionary.save');
    Route::get('/meu-dicionario', [DictionaryController::class, 'index'])->name('dictionary.index');
});


Route::get('/aula-ao-vivo', function () {
    abort_unless(auth()->check(), 403);

    $link = Settings::get('zoom_link');

    abort_if(!$link, 404);

    return redirect($link);
});

Route::get('/teste/{assessment}', [AssessmentController::class, 'teste'])
    ->name('teste');


// Criamos uma rota que recebe o ID do curso. Ela exige que o usuário esteja logado (auth).
Route::get('/modulo/{class}/certificado', function (ClassModel $class) {
    $student = auth()->user();

    // Verificação de segurança: O aluno realmente tem progresso no módulo?
    // Aqui assume-se que seu Model Class tem o atributo 'progress' calculado
    if (($class->progress ?? 0) < 95) {
        return redirect()->back()->with('error', 'Progresso insuficiente para gerar certificado.');
    }

    // Dados para o certificado
    $data = [
        'student' => $student,
        'module'  => $class->name,
        'course'  => $class->course->name ?? 'Curso Atitude Idiomas',
        'date'    => now()->format('d/m/Y'),
        'hours'   => $class->lessons()->count() * 1.0, // Você pode calcular isso dinamicamente com base nas aulas do módulo
    ];

    $pdf = Pdf::loadView('certificates.default', $data)
        ->setPaper('a4', 'landscape');

    return $pdf->stream("Certificado_{$class->name}.pdf");
})->name('student.module.certificate')->middleware('auth');


Route::get('/debug-student/{id}', function ($id) {
    // Busca o usuário e o aluno relacionado
    $user = User::with('student')->findOrFail($id);
    $student = $user->student;

    if (!$student) {
        return response()->json(['error' => 'Usuário não possui perfil de aluno'], 404);
    }

    $courses = Course::all();
    $debugData = [
        'aluno' => $student->name,
        'idioma_aluno' => $student->language,
        'relatorio' => []
    ];

    foreach ($courses as $course) {
        // Valida se o curso é do idioma do aluno
        $isLanguageMatch = ($student->language == $course->language || $student->language == 'both');

        if (!$isLanguageMatch) continue;

        $isCourseReleased = $course->isReleasedForStudent($student->id); //

        $courseInfo = [
            'curso' => $course->name,
            'liberado' => $isCourseReleased ? 'Sim' : 'Não (Conclua o curso anterior de ' . $course->language . ')',
            'progresso_total' => $course->calculateProgress($student->id) . '%',
            'modulos' => []
        ];

        foreach ($course->classes as $index => $class) {
            $isModuleLocked = false;
            if ($index > 0) {
                $previousClass = $course->classes[$index - 1];
                // Verifica conclusão baseada em avaliações
                if (!$previousClass->isCompletedByStudent($student->id)) {
                    $isModuleLocked = true;
                }
            }

            // Busca avaliações pendentes específicas deste módulo/classe
            $pendingInModule = [];

            // Coleta todas as lições deste módulo que têm avaliações
            foreach ($class->lessons as $lesson) {
                foreach ($lesson->assessments as $assessment) {
                    $totalQuestions = $assessment->questions->count(); //

                    // Conta respostas do aluno para as questões desta avaliação
                    $answeredCount = Answer::where('user_id', $user->id)
                        ->whereIn('question_id', $assessment->questions->pluck('id'))
                        ->count();

                    if ($answeredCount < $totalQuestions) {
                        $pendingInModule[] = [
                            'licao' => $lesson->title,
                            'avaliacao_id' => $assessment->id,
                            'questoes_faltantes' => $totalQuestions - $answeredCount
                        ];
                    }
                }
            }

            $courseInfo['modulos'][] = [
                'nome' => $class->name,
                'acesso' => $isModuleLocked ? 'Bloqueado' : 'Liberado',
                'concluido' => $class->isCompletedByStudent($student->id),
                'avaliacoes_pendentes' => $pendingInModule
            ];
        }

        $debugData['relatorio'][] = $courseInfo;
    }

    return response()->json($debugData, 200, [], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
})->middleware(['auth']);
