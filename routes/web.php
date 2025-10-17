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

// Route::get('/tradutor', function () {
//     $result = GoogleTranslate::source('pt-br')
//         ->target('es')
//         ->translate('esta é minha casa');

//     $result->getTranslatedText();

//     $result->getAlternativeTranslations();

//     $result->getSourceText();
//     $result->getSourceLanguage();
// });

Route::get('/tradutor', [TranslatorController::class, 'index'])->name('translator.index');
Route::post('/tradutor', [TranslatorController::class, 'translate'])->name('translator.translate');
Route::post('/translator/ajax-translate', [TranslatorController::class, 'ajaxTranslate'])->name('translator.ajaxTranslate');


Route::middleware(['auth'])->group(function () {
    Route::get('/texts', [TextController::class, 'index'])->name('texts.index');
    Route::get('/texts/{text}', [TextController::class, 'show'])->name('texts.show');
    Route::post('/dictionary/save', [TextController::class, 'saveWord'])->name('dictionary.save');
    Route::get('/meu-dicionario', [DictionaryController::class, 'index'])->name('dictionary.index');
});
