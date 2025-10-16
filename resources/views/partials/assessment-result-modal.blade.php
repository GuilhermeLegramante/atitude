<div id="resultModal"
    class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 backdrop-blur-sm p-4">
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl w-full max-w-3xl relative flex flex-col max-h-[90vh] overflow-hidden">

        {{-- Botão fechar --}}
        <button onclick="closeResultModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 z-10">
            <x-heroicon-o-x-mark class="w-6 h-6" />
        </button>

        {{-- Cabeçalho da modal --}}
        <h2 class="text-xl font-bold text-gray-900 dark:text-white mb-4 flex items-center gap-2 px-6 pt-6">
            <x-heroicon-o-check-circle class="w-6 h-6 text-[#c0ff01]" />
            Minhas Respostas
        </h2>

        {{-- Conteúdo com scroll --}}
        <div id="resultContent" class="flex-1 overflow-y-auto px-6 pb-6 space-y-4 text-gray-800 dark:text-gray-200">
            <p class="text-center text-gray-500 dark:text-gray-400 mt-10">Carregando respostas...</p>
        </div>
    </div>
</div>

<script>
    async function openResultModal(assessmentId) {
        const modal = document.getElementById('resultModal');
        const content = document.getElementById('resultContent');

        modal.classList.remove('hidden');
        content.innerHTML =
            '<div class="flex flex-col items-center justify-center py-6"><div class="w-6 h-6 border-4 border-[#c0ff01] border-t-transparent rounded-full animate-spin mb-3"></div><p class="text-gray-500 dark:text-gray-400">Carregando respostas...</p></div>';

        try {
            const response = await fetch(`/assessments/${assessmentId}/answers`);
            const data = await response.text();
            content.innerHTML = data;
        } catch (error) {
            content.innerHTML =
                '<p class="text-center text-red-500">Erro ao carregar suas respostas. Tente novamente.</p>';
        }
    }

    function closeResultModal() {
        document.getElementById('resultModal').classList.add('hidden');
    }
</script>
