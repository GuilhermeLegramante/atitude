<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class BlockAlunosFromAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        // Permite acesso à rota de logout do Filament
        if ($request->is('admin/logout')) {
            return $next($request);
        }

        // Se não estiver logado → deixa o Filament redirecionar normalmente
        if (! $user) {
            return $next($request);
        }

        // Se for aluno → bloqueia o acesso ao painel
        if ($user->hasRole('aluno')) {
            return redirect()->route('home')->with('error', 'Acesso restrito ao painel administrativo.');
        }

        return $next($request);
    }
}
