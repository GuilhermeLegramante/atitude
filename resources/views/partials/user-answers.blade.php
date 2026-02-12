{{-- Cabeçalho com pontuação --}}
<div class="text-center mb-6">
    <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-1">Resultado da Avaliação</h3>
    <p class="text-gray-600 dark:text-gray-400 mb-3">
        Você acertou <strong>{{ $correctAnswers }}</strong> de <strong>{{ $totalQuestions }}</strong> questões
        (<span class="text-[#c0ff01] font-semibold">{{ $scorePercent }}%</span>)
    </p>
    <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
        <div class="h-3 bg-[#c0ff01]" style="width: {{ $scorePercent }}%;"></div>
    </div>
</div>

{{-- Lista das questões --}}
<div class="space-y-6">
    @foreach ($questions as $question)
        @php $answer = $question->answers->first(); @endphp
        <div class="border border-gray-200 dark:border-gray-700 rounded-xl p-5 bg-white dark:bg-gray-800 shadow-sm">
            <h3 class="font-semibold text-gray-800 dark:text-gray-100 mb-2">
                {{ $loop->iteration }}. {{ $question->question_text }}
            </h3>

            {{-- Áudio --}}
            @if ($question->audio_path)
                <audio controls class="mt-2 w-full">
                    <source src="{{ asset('storage/' . $question->audio_path) }}" type="audio/mpeg">
                </audio>
            @endif

            {{-- Imagem --}}
            @if ($question->image_path)
                <img src="{{ asset('storage/' . $question->image_path) }}"
                    class="mt-3 rounded-xl max-h-64 object-contain border border-gray-300 dark:border-gray-600">
            @endif

            {{-- Resposta do aluno --}}
            @if ($answer)
                <p class="text-sm text-gray-600 dark:text-gray-300 mb-1">
                    <strong>Sua resposta:</strong>
                    {{ $answer->answer_text ?? ($answer->alternative->alternative_text ?? '—') }}
                </p>

                {{-- Exibição de Áudio enviado pelo aluno --}}
                @if ($answer->audio_path)
                    <div
                        class="mt-2 p-3 bg-gray-50 dark:bg-gray-900 rounded-lg border border-gray-200 dark:border-gray-700">
                        <p class="text-xs font-bold text-gray-500 uppercase mb-2 flex items-center gap-1">
                            <x-heroicon-o-microphone class="w-4 h-4" /> Áudio Enviado
                        </p>
                        <audio controls class="w-full h-10">
                            <source src="{{ Storage::url($answer->audio_path) }}" type="audio/mpeg">
                            Seu navegador não suporta áudio.
                        </audio>
                    </div>
                @endif

                {{-- Exibição de PDF enviado pelo aluno --}}
                @if ($answer->pdf_path)
                    <div class="mt-2">
                        <a href="{{ Storage::url($answer->pdf_path) }}" target="_blank"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-white dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-200 hover:bg-gray-50 dark:hover:bg-gray-800 transition shadow-sm">
                            <x-heroicon-o-document-text class="w-5 h-5 text-red-500" />
                            Visualizar PDF Enviado
                            <x-heroicon-o-arrow-top-right-on-square class="w-4 h-4 text-gray-400" />
                        </a>
                    </div>
                @endif
        </div>

        {{-- Status da correção --}}
        @if ($answer->checked)
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
            @else
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                    Corrigida, mas sem nota definida.
                </p>
            @endif
        @else
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1 flex items-center gap-1">
                <x-heroicon-o-clock class="w-5 h-5 text-yellow-500" />
                Aguardando correção do professor...
            </p>
        @endif

        {{-- Comentário do professor --}}
        @if ($answer->note)
            <div class="mt-2 p-3 bg-gray-100 dark:bg-gray-700 rounded-lg border-l-4 border-[#c0ff01]">
                <p class="text-sm text-gray-700 dark:text-gray-200">
                    <strong>Comentário do professor:</strong> {{ $answer->note }}
                </p>
            </div>
        @endif
    @else
        <p class="text-gray-500 dark:text-gray-400">Você ainda não respondeu esta questão.</p>
    @endif

    {{-- Gabarito --}}
    @if ($question->gabarito)
        <div class="mt-3 border-t border-gray-200 dark:border-gray-700 pt-2">
            <p class="text-sm text-gray-700 dark:text-gray-300"><strong>Gabarito:</strong>
                {{ $question->gabarito }}</p>
        </div>
    @endif
</div>
@endforeach
</div>
