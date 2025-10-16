<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LessonController;
use App\Mail\FirstEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;

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
