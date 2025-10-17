@extends('master')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-[#1e2030]/90 backdrop-blur-lg rounded-2xl shadow-lg text-white">
        <!-- Título do texto -->
        <h1 class="text-2xl font-semibold mb-6 text-[#c0ff01]">{{ $text->title }}</h1>

        <!-- Conteúdo do texto -->
        <div class="text-lg leading-relaxed max-h-[70vh] overflow-y-auto p-2">
            @foreach (explode(' ', $text->content) as $word)
                <span class="cursor-pointer hover:text-[#c0ff01] transition word" data-word="{{ $word }}">
                    {{ $word }}
                </span>
                <span> </span>
            @endforeach
        </div>
    </div>

    <!-- Modal para tradução -->
    <div id="wordModal" class="fixed inset-0 bg-black/60 hidden z-50 flex items-center justify-center">
        <div
            class="bg-gray-100 dark:bg-gray-900 p-6 rounded-2xl max-w-md w-full relative shadow-xl overflow-y-auto max-h-[80vh]">
            <!-- Botão fechar -->
            <button onclick="closeModal()"
                class="absolute top-3 right-3 text-gray-800 dark:text-white text-2xl font-bold hover:text-red-500 transition">
                &times;
            </button>

            <!-- Palavra -->
            <h2 class="text-xl font-semibold mb-2 text-[#c0ff01]" id="modalWord"></h2>

            <!-- Tradução -->
            <p class="mb-6 text-gray-900 dark:text-gray-200" id="modalTranslation">Traduzindo...</p>

            <!-- Botões -->
            <button onclick="saveWord()"
                class="bg-[#c0ff01] text-[#111827] px-4 py-2 rounded-md font-semibold shadow hover:bg-[#aaff00] hover:scale-105 transition transform">
                Salvar no dicionário
            </button>
            <button onclick="closeModal()"
                class="bg-[#374151] dark:bg-gray-700 text-white px-4 py-2 rounded-md font-semibold shadow hover:bg-gray-600 hover:scale-105 transition transform">
                Fechar
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let currentWord = '';

            const modal = document.getElementById('wordModal');
            const modalContent = modal.querySelector('div');
            const modalWord = document.getElementById('modalWord');
            const modalTranslation = document.getElementById('modalTranslation');

            // Abrir modal com animação
            window.openModal = function() {
                modal.classList.remove('hidden');
                modalContent.style.transform = 'scale(1)';
                modalContent.style.opacity = '1';
            }

            // Fechar modal com animação
            window.closeModal = function() {
                modalContent.style.transform = 'scale(0.9)';
                modalContent.style.opacity = '0';
                setTimeout(() => modal.classList.add('hidden'), 150);
            }

            // Salvar palavra no dicionário do aluno
            window.saveWord = async function() {
                const translation = modalTranslation.innerText;
                try {
                    const res = await fetch('{{ route('dictionary.save') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            word: currentWord,
                            translation
                        })
                    });
                    const data = await res.json();
                    if (data.success) {
                        alert('Palavra salva no seu dicionário!');
                        closeModal();
                    }
                } catch (err) {
                    alert('Erro ao salvar a palavra.');
                }
            }

            // Clique nas palavras do texto
            document.querySelectorAll('.word').forEach(el => {
                el.addEventListener('click', async () => {
                    currentWord = el.dataset.word;
                    modalWord.innerText = currentWord;
                    modalTranslation.innerText = 'Traduzindo...';
                    openModal();

                    try {
                        const res = await fetch('{{ route('translator.ajaxTranslate') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                text: currentWord,
                                source: 'auto',
                                target: 'pt'
                            })
                        });

                        const data = await res.json();
                        if (data.translatedText) {
                            modalTranslation.innerText = data.translatedText;
                        } else {
                            modalTranslation.innerText = data.error || 'Erro na tradução';
                        }
                    } catch (err) {
                        modalTranslation.innerText = 'Erro na tradução';
                    }
                });
            });
        });
    </script>

    <style>
        #wordModal div {
            transform: scale(0.9);
            opacity: 0;
            transition: all 0.2s ease-in-out;
        }
    </style>
@endsection
