<section class="mt-10">
    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center gap-2">
        <x-heroicon-o-academic-cap class="w-6 h-6 text-sky-700" />
        Avaliações da Aula
    </h2>

    @forelse($lesson->assessments as $assessment)
        <div
            class="group relative overflow-hidden bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 mb-4 p-5">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3">
                <div>
                    <h3
                        class="font-semibold text-lg text-gray-800 dark:text-gray-100 group-hover:text-sky-600 transition-colors">
                        {{ $assessment->name }}
                    </h3>
                    @if ($assessment->description)
                        <p class="text-gray-500 dark:text-gray-400 mt-1 text-sm leading-relaxed">
                            {{ $assessment->description }}
                        </p>
                    @endif
                </div>

                <button
                    class="mt-2 sm:mt-0 inline-flex items-center justify-center gap-2 px-4 py-2 rounded-xl font-medium bg-sky-600 text-white hover:bg-sky-700 focus:ring-2 focus:ring-sky-400 transition-all"
                    onclick="openAssessmentModal({{ $assessment->id }})">
                    <x-heroicon-o-play class="w-4 h-4" />
                    Iniciar
                </button>
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
