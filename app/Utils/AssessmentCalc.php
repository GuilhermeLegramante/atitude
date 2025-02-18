<?php

namespace App\Utils;


class AssessmentCalc
{
    /**
     * Calcula a nota da avaliação de acordo com as respostas
     */
    public static function getScore($answers): float
    {
        $total = count($answers);  // Total de respostas
        $score = 0;  // Inicializa a variável de acertos

        // Conta os acertos
        foreach ($answers as $answer) {
            if ($answer->is_correct) {
                $score++;
            }
        }

        // Calculando o percentual de acertos
        $percentage = ($total > 0) ? ($score / $total) * 100 : 0;

        return round($percentage, 2);
    }
}
