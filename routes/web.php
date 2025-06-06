<?php

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

Route::get('/payments/{payment}/receipt', function (Payment $payment) {
    $pdf = Pdf::loadView('payments.receipt', compact('payment'));

    // Forçar download:
    // return $pdf->download('recibo_pagamento_'.$payment->id.'.pdf');

    // Ou abrir direto no navegador:
    return $pdf->stream('recibo_pagamento_' . $payment->id . '.pdf');
})->name('payments.receipt');
