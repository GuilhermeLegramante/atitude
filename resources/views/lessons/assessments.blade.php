<section class="mt-10">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
        <x-heroicon-o-academic-cap class="w-6 h-6 text-sky-700" />
        Avaliações da Aula
    </h2>

    @forelse($lesson->assessments as $assessment)
        <div
            class="group relative overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 mb-4 p-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div class="w-full">
                    {{-- Título e descrição --}}
                    <h3
                        class="font-semibold text-lg text-gray-800 dark:text-gray-100 group-hover:text-[#c0ff01] transition-colors">
                        {{ $assessment->name }}
                    </h3>

                    @if ($assessment->description)
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm leading-relaxed">
                            {{ $assessment->description }}
                        </p>
                    @endif

                    {{-- Mídias associadas --}}
                    @if ($assessment->image_path || $assessment->audio_path)
                        <div class="mt-3 flex flex-col sm:flex-row items-start sm:items-center gap-4">
                            {{-- Imagem --}}
                            @if ($assessment->image_path)
                                <div
                                    class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                                    <img src="{{ Storage::url($assessment->image_path) }}" alt="Imagem da avaliação"
                                        class="max-h-40 w-auto object-cover">
                                </div>
                            @endif

                            {{-- Áudio --}}
                            @if ($assessment->audio_path)
                                <div
                                    class="flex items-center gap-2 bg-gray-50 dark:bg-gray-900 border border-gray-200 dark:border-gray-700 rounded-xl px-4 py-3 shadow-sm w-full sm:w-auto">
                                    <x-heroicon-o-musical-note class="w-5 h-5 text-[#c0ff01]" />
                                    <audio controls class="w-64">
                                        <source src="{{ Storage::url($assessment->audio_path) }}" type="audio/mpeg">
                                        Seu navegador não suporta o elemento de áudio.
                                    </audio>
                                </div>
                            @endif
                        </div>
                    @endif
                </div>

                @php
                    $userAnswerExists = $assessment->questions->flatMap->answers
                        ->where('user_id', auth()->id())
                        ->isNotEmpty();
                @endphp

                <div class="flex gap-2 mt-2 sm:mt-0">
                    <button
                        class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-medium bg-sky-600 text-white hover:bg-sky-700 focus:ring-2 focus:ring-sky-400 transition-all"
                        onclick="openAssessmentModal({{ $assessment->id }})">
                        <x-heroicon-o-play class="w-4 h-4" />
                        Iniciar
                    </button>

                    @if ($userAnswerExists)
                        <button
                            class="inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-medium border border-sky-600 text-sky-700 hover:bg-sky-100 dark:hover:bg-gray-700 focus:ring-2 focus:ring-sky-400 transition-all"
                            onclick="openResultModal({{ $assessment->id }})">
                            <x-heroicon-o-eye class="w-4 h-4" />
                            Respostas
                        </button>
                    @endif
                </div>



            </div>

            {{-- Linha de destaque sutil ao hover --}}
            <div class="absolute bottom-0 left-0 w-0 h-1 bg-sky-600 transition-all duration-300 group-hover:w-full">
            </div>
        </div>
    @empty
        <div
            class="text-center py-10 bg-gray-50 dark:bg-gray-900 rounded-xl border border-gray-200 dark:border-gray-700">
            <x-heroicon-o-clipboard-document-list class="w-10 h-10 mx-auto text-gray-400 mb-3" />
            <p class="text-gray-500 dark:text-gray-400 text-sm">Nenhuma avaliação disponível para esta aula.</p>
        </div>
    @endforelse
</section>


@include('partials.assessment-modal')

@include('partials.assessment-result-modal')
