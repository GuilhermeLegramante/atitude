<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Expense;
use App\Models\Payment;
use Carbon\Carbon;

class GerarRecorrenciasFinanceiras extends Command
{
    protected $signature = 'financeiro:gerar-recorrencias';

    protected $description = 'Gera automaticamente as próximas despesas e pagamentos recorrentes';

    public function handle()
    {
        $this->info('Gerando recorrências de despesas...');
        $this->gerarDespesas();

        $this->info('Gerando recorrências de pagamentos...');
        $this->gerarPagamentos();

        $this->info('Recorrências geradas com sucesso!');
    }

    protected function gerarDespesas()
    {
        $despesas = Expense::where('is_recurring', true)
            ->where('due_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('recurrence_end_date')
                    ->orWhere('recurrence_end_date', '>=', now());
            })
            ->get();

        foreach ($despesas as $despesa) {
            $nextDueDate = match ($despesa->recurrence_type) {
                'semanal' => Carbon::parse($despesa->due_date)->addWeek(),
                'mensal' => Carbon::parse($despesa->due_date)->addMonth(),
                'anual' => Carbon::parse($despesa->due_date)->addYear(),
                default => null,
            };

            // Verifica se já existe uma recorrência futura para evitar duplicação
            $existe = Expense::where('description', $despesa->description)
                ->where('due_date', $nextDueDate)
                ->exists();

            if (!$nextDueDate || $existe || ($despesa->recurrence_end_date && $nextDueDate > $despesa->recurrence_end_date)) {
                continue;
            }

            $nova = $despesa->replicate();
            $nova->due_date = $nextDueDate;
            $nova->payment_date = null;
            $nova->status = 'aberto';
            $nova->created_at = now();
            $nova->updated_at = now();
            $nova->save();
        }
    }

    protected function gerarPagamentos()
    {
        $pagamentos = Payment::where('is_recurring', true)
            ->where('due_date', '<=', now())
            ->where(function ($q) {
                $q->whereNull('recurrence_end_date')
                    ->orWhere('recurrence_end_date', '>=', now());
            })
            ->get();

        foreach ($pagamentos as $pagamento) {
            $nextDueDate = match ($pagamento->recurrence_type) {
                'semanal' => Carbon::parse($pagamento->due_date)->addWeek(),
                'mensal' => Carbon::parse($pagamento->due_date)->addMonth(),
                'anual' => Carbon::parse($pagamento->due_date)->addYear(),
                default => null,
            };

            // Verifica se já existe uma recorrência futura para evitar duplicação
            $existe = Payment::where('description', $pagamento->description)
                ->where('due_date', $nextDueDate)
                ->exists();

            if (!$nextDueDate || $existe || ($pagamento->recurrence_end_date && $nextDueDate > $pagamento->recurrence_end_date)) {
                continue;
            }

            $novo = $pagamento->replicate();
            $novo->due_date = $nextDueDate;
            $novo->payment_date = null;
            $novo->status = 'aberto';
            $novo->created_at = now();
            $novo->updated_at = now();
            $novo->save();
        }
    }
}
