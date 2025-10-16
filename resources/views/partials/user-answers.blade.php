{{-- Cabeçalho com pontuação geral --}}
<div class="text-center mb-6">
    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-1">
        Resultado da Avaliação
    </h3>
    <p class="text-gray-600 dark:text-gray-400 mb-3">
        Você acertou <strong>{{ $correctAnswers }}</strong> de <strong>{{ $totalQuestions }}</strong> questões
        (<span class="text-[#c0ff01] font-semibold">{{ $scorePercent }}%</span>)
    </p>

    {{-- Barra de progresso --}}
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
        <div class="h-3 bg-[#c0ff01] transition-all duration-700" style="width: {{ $scorePercent }}%;"></div>
    </div>
</div>

{{-- Lista das questões e respostas --}}
<div class="space-y-6">
    @foreach ($questions as $question)
        @php
            $answer = $question->answers->first();
        @endphp

        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm">
            {{-- Enunciado --}}
            <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">
                {{ $loop->iteration }}. {{ $question->question_text }}
            </h3>

            {{-- Exibição de mídia (áudio ou imagem no enunciado) --}}
            @if ($question->audio_path)
                <audio controls class="mt-2 w-full">
                    <source src="{{ asset('storage/' . $question->audio_path) }}" type="audio/mpeg">
                    Seu navegador não suporta o elemento de áudio.
                </audio>
            @endif

            @if ($question->image_path)
                <img src="{{ asset('storage/' . $question->image_path) }}" alt="Imagem da questão"
                    class="mt-3 rounded-xl max-h-64 object-contain border border-gray-300 dark:border-gray-600">
            @endif

            {{-- Resposta do usuário --}}
            <div class="mt-3">
                @if ($answer)
                    <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                        <strong>Sua resposta:</strong>
                        {{ $answer->answer_text ?? ($answer->alternative->alternative_text ?? '—') }}
                    </p>

                    @if (!is_null($answer->is_correct))
                        <p class="text-sm mt-1 flex items-center gap-2">
                            @if ($answer->is_correct)
                                <x-heroicon-o-check class="w-5 h-5 text-green-500" />
                                <span class="text-green-600">Correta</span>
                            @else
                                <x-heroicon-o-x-mark class="w-5 h-5 text-red-500" />
                                <span class="text-red-600">Incorreta</span>
                            @endif
                        </p>
                    @endif

                    @if ($answer->note)
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                            <strong>Observação:</strong> {{ $answer->note }}
                        </p>
                    @endif
                @else
                    <p class="text-gray-500 dark:text-gray-400">Você ainda não respondeu esta questão.</p>
                @endif
            </div>

            {{-- Gabarito (para questões discursivas ou como referência geral) --}}
            @if ($question->gabarito)
                <div class="mt-3 border-t border-gray-200 dark:border-gray-700 pt-2">
                    <p class="text-sm text-gray-700 dark:text-gray-300">
                        <strong>Gabarito:</strong> {{ $question->gabarito }}
                    </p>
                </div>
            @endif
        </div>
    @endforeach
</div>
