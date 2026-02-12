<div class="p-6 bg-white dark:bg-gray-900 rounded-2xl shadow-lg border border-gray-200 dark:border-gray-800">
    <h2 class="text-2xl font-bold mb-3 text-gray-900 dark:text-white flex items-center gap-2">
        <x-heroicon-o-academic-cap class="w-6 h-6 text-[#c0ff01]" />
        {{ $assessment->name }}
    </h2>
    <p class="text-gray-600 dark:text-gray-300 mb-6 leading-relaxed">
        {{ $assessment->description }}
    </p>

    {{-- Mídias associadas --}}
    @if ($assessment->image_path || $assessment->audio_path)
        <div class="mt-3 mb-4 flex flex-col sm:flex-row items-start sm:items-center gap-4">
            {{-- Imagem --}}
            @if ($assessment->image_path)
                <div class="rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 shadow-sm">
                    <img src="{{ Storage::url($assessment->image_path) }}" alt="Imagem da avaliação"
                        class="max-h-60 w-auto object-cover">
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

    <form id="assessmentForm" data-id="{{ $assessment->id }}" class="space-y-6" enctype="multipart/form-data">
        @foreach ($assessment->questions as $question)
            <div
                class="border border-gray-100 dark:border-gray-700 rounded-xl p-5 bg-gray-50 dark:bg-gray-800 transition hover:shadow-sm">
                <h3 class="font-semibold text-lg text-gray-800 dark:text-gray-100 mb-3">
                    {{ $loop->iteration }}. {{ $question->question_text }}
                </h3>

                {{-- Tipos de questão --}}
                @if ($question->questionType->type_name === 'Objetiva')
                    <div class="space-y-2">
                        @foreach ($question->alternatives as $alt)
                            <label
                                class="flex items-center gap-3 p-2 rounded-lg border border-transparent hover:border-[#c0ff01] transition">
                                <input type="radio" name="answers[{{ $question->id }}]" value="{{ $alt->id }}"
                                    class="text-[#c0ff01] focus:ring-[#c0ff01]">
                                <span class="text-gray-700 dark:text-gray-300">{{ $alt->alternative_text }}</span>
                            </label>
                        @endforeach
                    </div>
                @elseif ($question->questionType->type_name === 'Discursiva')
                    <textarea name="answers[{{ $question->id }}][text]"
                        class="w-full mt-2 border border-gray-300 dark:border-gray-600 rounded-xl p-3 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-[#c0ff01] transition"
                        rows="4" placeholder="Digite sua resposta...">
                    </textarea>
                @elseif ($question->questionType->type_name === 'Upload de Áudio')
                    <div class="mt-3">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <x-heroicon-o-microphone class="w-5 h-5 text-[#c0ff01]" />
                            Enviar Áudio
                        </label>

                        <input type="file" name="answers[{{ $question->id }}][audio]" accept="audio/*"
                            class="w-full text-sm text-gray-600 dark:text-gray-300
            file:mr-4 file:py-2 file:px-4
            file:rounded-lg file:border-0
            file:text-sm file:font-semibold
            file:bg-[#c0ff01] file:text-gray-900
            hover:file:bg-[#b0e700] transition">
                    </div>
                @elseif ($question->questionType->type_name === 'Upload de PDF')
                    <div class="mt-3">
                        <label
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2 flex items-center gap-2">
                            <x-heroicon-o-document-text class="w-5 h-5 text-[#c0ff01]" />
                            Enviar PDF
                        </label>

                        <input type="file" name="answers[{{ $question->id }}][pdf]" accept="application/pdf"
                            class="w-full text-sm text-gray-600 dark:text-gray-300
            file:mr-4 file:py-2 file:px-4
            file:rounded-lg file:border-0
            file:text-sm file:font-semibold
            file:bg-[#c0ff01] file:text-gray-900
            hover:file:bg-[#b0e700] transition">
                    </div>
                @endif
            </div>
        @endforeach

        <button type="submit"
            class="w-full py-3 rounded-xl font-semibold bg-[#c0ff01] text-gray-900 hover:bg-[#b0e700] transition focus:ring-4 focus:ring-[#c0ff01]/50">
            Enviar Avaliação
        </button>
    </form>
</div>
<script>
    // Usar uma função nomeada ajuda no debug
    function handleAssessmentSubmit(e) {
        e.preventDefault();

        const form = e.currentTarget; // Garante que pega o formulário
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');

        // Impede cliques duplos se já estiver enviando
        if (submitBtn.disabled) return;

        submitBtn.disabled = true;
        submitBtn.innerText = 'Enviando...';

        fetch("{{ route('assessments.submit', $assessment->id) }}", {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(async response => {
                const data = await response.json();
                if (data.success) {
                    alert('Avaliação enviada com sucesso!');
                    window.location.reload();
                } else {
                    alert('Erro: ' + (data.message || 'Falha no envio.'));
                }
            })
            .catch(error => {
                console.error('Fetch Error:', error);
                alert('Erro de conexão ou servidor.');
            })
            .finally(() => {
                submitBtn.disabled = false;
                submitBtn.innerText = 'Enviar Avaliação';
            });
    }

    // Inicialização segura
    document.addEventListener('DOMContentLoaded', () => {
        const form = document.getElementById('assessmentForm');
        if (form) {
            form.addEventListener('submit', handleAssessmentSubmit);
        }
    });
</script>
