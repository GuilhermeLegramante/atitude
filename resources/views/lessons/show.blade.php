Ah, desculpa! Provavelmente o final da tag do `iframe` ficou corrompido no bloco anterior (apareceu como `frame>` em vez
de `</iframe>`), o que quebra a renderização do HTML e faz a página ficar totalmente em branco.

Aqui está o código 100% corrigido, limpo e pronto para colar. Pode substituir todo o conteúdo do seu arquivo por este:

```html
@extends('master')

@section('title', 'Aula - Atitude Idiomas')

@section('content')
    <div class="min-h-screen bg-[#020916] text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <main class="lg:col-span-2" x-data="lessonPlayer()">
                    <div
                        class="bg-[#0b1528]/80 backdrop-blur-md rounded-2xl shadow-2xl border border-white/5 overflow-hidden">

                        <div class="relative bg-black rounded-t-2xl overflow-hidden shadow-inner" style="aspect-ratio:16/9">
                            @php
                                $videoUrl = $lesson->video_link;

                                if (Str::contains($videoUrl, 'youtu.be')) {
                                    // Transforma https://youtu.be/ID em https://www.youtube.com/watch?v=ID
                                    $id = last(explode('/', $videoUrl));
                                    $videoUrl = 'https://www.youtube.com/watch?v=' . $id;
                                }
                            @endphp

                            <iframe class="absolute top-0 left-0 w-full h-full"
                                src="{{ Str::contains($videoUrl, 'youtube.com') ? preg_replace(['/watch\?v=/'], ['embed/'], $videoUrl) : $videoUrl }}"
                                title="{{ $lesson->title }}" frameborder="0"
                                allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen>
                            </iframe>
                        </div>

                        <div class="p-6 border-t border-white/5">
                            <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                                <div>
                                    <h1 class="text-2xl font-bold tracking-tight text-white">{{ $lesson->title }}</h1>
                                    <p class="text-sm text-gray-400 mt-1 font-medium">
                                        {{ $lesson->class->course->name ?? '' }}
                                    </p>

                                    <div class="mt-4 flex items-center gap-4 text-sm">
                                        <div class="flex items-center gap-3">
                                            <img src="{{ $lesson->class->course->user->avatar ? asset('storage/' . $lesson->class->course->user->avatar) : asset('img/mascote.png') }}"
                                                alt="{{ $lesson->class->course->user->name }}"
                                                class="w-12 h-12 rounded-full border border-white/10 object-cover shadow-md">
                                            <div>
                                                <div class="font-semibold text-white">
                                                    {{ $lesson->class->course->user->name ?? 'Professor' }}</div>
                                                <div class="text-xs text-gray-400">Instrutor principal</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 flex-wrap sm:self-start">
                                    {{-- Relatar problema --}}
                                    <a href="#"
                                        class="px-3 py-2 rounded-xl border border-white/10 text-xs font-medium text-gray-300 hover:text-white hover:bg-white/5 transition duration-300">
                                        Relatar Problema
                                    </a>

                                    {{-- Marcar como assistida --}}
                                    <form method="POST" action="{{ route('lessons.toggleWatched', $lesson->id) }}">
                                        @csrf
                                        <button type="submit"
                                            class="px-4 py-2 rounded-xl text-xs font-bold transition duration-300 shadow-md 
                                            {{ $lesson->watched_by_student
                                                ? 'bg-emerald-600/20 border border-emerald-500/30 text-emerald-400 hover:bg-emerald-600/30'
                                                : 'bg-[#82cd29] text-[#020916] hover:bg-[#76c022]' }}">
                                            {{ $lesson->watched_by_student ? '✔ Assistida' : 'Marcar como assistida' }}
                                        </button>
                                    </form>
                                </div>
                            </div>

                            @if ($lesson->description)
                                <div class="mt-6 pt-6 border-t border-white/5 text-sm text-gray-300 leading-relaxed">
                                    {!! nl2br(e($lesson->description)) !!}
                                </div>
                            @endif
                        </div>
                    </div>

                    @include('lessons.assessments')

                    <div class="mt-8">
                        <div class="bg-[#0b1528]/80 backdrop-blur-md rounded-2xl shadow-2xl border border-white/5 p-6">
                            <div class="flex items-center justify-between border-b border-white/5 pb-4">
                                <h2 class="text-lg font-bold tracking-wide text-white">Recursos e Atividades</h2>
                            </div>

                            <ul class="mt-4 space-y-3">
                                @forelse ($lesson->materials ?? [] as $res)
                                    <li
                                        class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 p-4 rounded-xl bg-white/5 border border-white/5 hover:border-white/10 transition">
                                        <div class="flex items-center gap-4">
                                            <div
                                                class="w-11 h-11 rounded-xl bg-[#82cd29]/10 border border-[#82cd29]/20 flex items-center justify-center text-[#82cd29] font-bold text-xs tracking-wider">
                                                {{ strtoupper(substr($res->name, 0, 2)) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-white text-sm">{{ $res->name }}</div>
                                                <div class="text-xs text-gray-400 mt-0.5">
                                                    {{ $res->note ?? 'Material complementar de apoio' }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2 self-end sm:self-auto">
                                            <a href="{{ Storage::url($res->url) }}" target="_blank"
                                                class="px-3 py-1.5 rounded-lg border border-white/10 text-xs font-medium text-gray-300 hover:text-white hover:bg-white/5 transition">Abrir</a>

                                            <a href="{{ Storage::url($res->url) }}" download
                                                class="px-3 py-1.5 rounded-lg bg-white/10 text-xs font-medium text-white hover:bg-white/20 transition">Baixar</a>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-sm text-gray-400 py-2">Nenhum material de apoio anexado a esta aula.
                                    </li>
                                @endforelse
                            </ul>

                            <div class="mt-8 pt-8 border-t border-white/5">
                                <h3 class="text-base font-bold text-white tracking-wide">Perguntas & Respostas</h3>
                                <div class="mt-4">
                                    <form class="flex gap-3" @submit.prevent="postQuestion">
                                        <input x-model="newQuestion"
                                            class="flex-1 rounded-xl bg-white/5 border border-white/10 px-4 py-2.5 text-sm text-white placeholder-gray-500 focus:outline-none focus:border-[#82cd29] transition"
                                            placeholder="Pergunte algo sobre a aula...">
                                        <button type="submit"
                                            class="px-5 py-2.5 rounded-xl bg-[#82cd29] text-[#020916] font-bold text-sm hover:bg-[#76c022] transition shadow-md">Enviar</button>
                                    </form>

                                    <div class="mt-4 space-y-3">
                                        <template x-for="q in questions" :key="q.id">
                                            <div class="p-4 border border-white/5 rounded-xl bg-white/5">
                                                <div class="text-xs font-bold text-[#82cd29]" x-text="q.user"></div>
                                                <div class="text-sm text-gray-200 mt-1" x-text="q.text"></div>
                                            </div>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>

                <div class="space-y-6">
                    <aside class="bg-[#0b1528]/80 backdrop-blur-md rounded-2xl shadow-2xl border border-white/5 p-5 h-fit">
                        <div>
                            <div class="text-[11px] font-bold uppercase tracking-wider text-gray-500">Curso</div>
                            <div class="font-bold text-lg text-white mt-0.5">{{ $lesson->class->course->name ?? 'Módulo' }}
                            </div>
                            <div class="text-xs text-gray-400 mt-0.5">
                                {{ $lesson->class->lessons->count() ?? '—' }} aulas neste módulo
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-white/5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Próxima Aula</h4>

                            @if ($lesson->next_lesson)
                                <a href="{{ route('lessons.show', $lesson->next_lesson->id) }}"
                                    class="mt-2 block rounded-xl p-3 bg-white/5 border border-white/5 hover:border-white/10 hover:bg-white/10 transition group">
                                    <div
                                        class="text-sm font-semibold text-white group-hover:text-[#82cd29] transition duration-200">
                                        {{ $lesson->next_lesson->title }}</div>
                                </a>
                            @else
                                <div
                                    class="mt-2 block rounded-xl p-3.5 bg-emerald-600/10 border border-emerald-500/20 text-emerald-400 text-sm font-medium">
                                    Esta é a última aula do módulo 🎓
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 pt-6 border-t border-white/5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Progresso do módulo</h4>
                            <div class="mt-2">
                                <div class="w-full bg-white/10 rounded-full h-2 overflow-hidden">
                                    <div style="width: {{ $lesson->class->progress ?? 0 }}%"
                                        class="h-2 rounded-full bg-gradient-to-r from-[#82cd29] to-emerald-400"></div>
                                </div>
                                <div class="text-xs text-gray-400 mt-2 font-medium">
                                    <strong class="text-white">{{ $lesson->class->progress ?? 0 }}%</strong> completo
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 pt-6 border-t border-white/5">
                            <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400 mb-2">Aulas do módulo</h4>

                            @if ($lesson->class->lessons->count())
                                <ul class="space-y-1.5 max-h-64 overflow-y-auto pr-2 custom-scrollbar">
                                    @foreach ($lesson->class->lessons as $classLesson)
                                        @php
                                            $isCurrent = $classLesson->id === $lesson->id;
                                        @endphp
                                        <li>
                                            <a href="{{ route('lessons.show', $classLesson->id) }}"
                                                class="flex items-center gap-3 p-2.5 rounded-lg text-xs font-medium transition group
                                                {{ $isCurrent ? 'bg-[#82cd29]/10 text-[#82cd29] border border-[#82cd29]/20' : 'text-gray-300 hover:text-white hover:bg-white/5' }}">

                                                <span class="w-4 h-4 flex items-center justify-center shrink-0">
                                                    @if ($classLesson->watched_by_student ?? false)
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-4 h-4 text-emerald-400" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @else
                                                        <div
                                                            class="w-2 h-2 rounded-full {{ $isCurrent ? 'bg-[#82cd29]' : 'bg-gray-600 group-hover:bg-gray-400' }}">
                                                        </div>
                                                    @endif
                                                </span>

                                                <span class="truncate">{{ $classLesson->title }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-xs text-gray-500">Nenhuma aula disponível neste módulo.</p>
                            @endif
                        </div>
                    </aside>

                    <div class="bg-[#0b1528]/80 backdrop-blur-md rounded-2xl shadow-2xl border border-white/5 p-5 h-fit">
                        <h4 class="text-xs font-bold uppercase tracking-wider text-gray-400">Certificado do Módulo</h4>

                        @php
                            $moduleProgress = $lesson->class->progress ?? 0;
                        @endphp

                        @if ($moduleProgress >= 95)
                            <p class="text-xs text-emerald-400 mt-2 font-semibold flex items-center gap-1.5">
                                <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                                Parabéns! Módulo concluído.
                            </p>

                            <a href="{{ route('student.module.certificate', ['class' => $lesson->class->id]) }}"
                                target="_blank"
                                class="mt-3 inline-block px-4 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-[#82cd29] text-[#020916] font-bold text-sm hover:opacity-90 transition w-full text-center shadow-lg shadow-emerald-500/10">
                                🎓 Emitir Certificado
                            </a>
                        @else
                            <p class="text-xs text-gray-400 mt-2 leading-relaxed">
                                Conclua 95% das aulas para liberar seu certificado de proficiência deste módulo.
                                <span class="block mt-2 font-medium text-gray-300">Progresso atual: <strong
                                        class="text-emerald-400">{{ $moduleProgress }}%</strong></span>
                            </p>

                            <span
                                class="mt-3 inline-block px-4 py-2.5 rounded-xl bg-white/5 border border-white/5 text-gray-500 text-xs font-bold tracking-wide w-full text-center cursor-not-allowed select-none">
                                🔒 Módulo em andamento
                            </span>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <style>
        /* Estilização suave para a barra de rolagem da lista de aulas */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.2);
        }
    </style>

    <script>
        function lessonPlayer() {
            return {
                newQuestion: '',
                questions: [],
                transcriptOpen: false,
                toggleTranscript() {
                    this.transcriptOpen = !this.transcriptOpen;
                    alert('Transcrição: aqui você pode mostrar a transcrição ou abrir um painel lateral.');
                },
                togglePin() {
                    alert('Pin toggled - aqui você pode implementar picture-in-picture ou pop-out player.');
                },
                postQuestion() {
                    if (!this.newQuestion) return;
                    this.questions.unshift({
                        id: Date.now(),
                        user: 'Você',
                        text: this.newQuestion
                    });
                    this.newQuestion = '';
                }
            }
        }
    </script>

    <script>
        function openAssessmentModal(id) {
            const modal = document.getElementById('assessmentModal');
            const content = document.getElementById('assessmentContent');

            modal.classList.remove('hidden');
            content.innerHTML = '<p class="text-center text-gray-400">Carregando...</p>';

            fetch(`/assessments/${id}/modal`)
                .then(res => res.text())
                .then(html => content.innerHTML = html);
        }

        function closeAssessmentModal() {
            document.getElementById('assessmentModal').classList.add('hidden');
        }

        document.addEventListener('submit', function(e) {
            if (e.target.id === 'assessmentForm') {
                e.preventDefault();
                const id = e.target.dataset.id;
                const formData = new FormData(e.target);

                fetch(`/assessments/${id}/submit`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: formData
                    })
                    .then(r => r.json())
                    .then(res => {
                        if (res.success) {
                            alert('Avaliação enviada com sucesso!');
                            closeAssessmentModal();
                        }
                    });
            }
        });
    </script>
@endsection