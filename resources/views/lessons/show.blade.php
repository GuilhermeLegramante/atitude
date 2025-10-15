@extends('master')

@section('title', 'Aula - Atitude Idiomas')

@section('content')
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- PLAYER + NOTES (MAIN) -->
            <main class="lg:col-span-2" x-data="lessonPlayer()">
                <div class="bg-white rounded-2xl shadow border overflow-hidden">
                    <!-- ✅ Player do YouTube -->
                    <div class="relative bg-black rounded-t-2xl overflow-hidden" style="aspect-ratio:16/9">
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


                    <!-- LESSON INFO -->
                    <div class="p-6 border-t">
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h1 class="text-2xl font-semibold">{{ $lesson->title }}</h1>
                                <p class="text-sm text-slate-500 mt-1">
                                    {{ $lesson->course->name ?? '' }}
                                </p>

                                <div class="mt-4 flex items-center gap-4 text-sm text-slate-600">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $lesson->course->user->avatar ?? asset('img/avatar-placeholder.png') }}"
                                            alt="Professor" class="w-9 h-9 rounded-full object-cover">
                                        <div>
                                            <div class="font-medium">{{ $lesson->class->course->user->name ?? 'Professor' }}
                                            </div>
                                            <div class="text-xs text-slate-500">{{ $lesson->duration ?? '—' }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">
                                <a href="{{ $lesson->resources_download ?? '#' }}"
                                    class="hidden sm:inline-flex items-center gap-2 px-3 py-2 rounded-lg bg-sky-600 text-white font-medium">Baixar
                                    materiais</a>
                                <a href="#" class="px-3 py-2 rounded-lg border text-sm">Relatar problema</a>
                            </div>
                        </div>

                        <div class="mt-4 text-sm text-slate-700">
                            {!! nl2br(e($lesson->description ?? '')) !!}
                        </div>
                    </div>
                </div>

                <!-- TABS: Resources / Transcript / Q&A -->
                <div class="mt-6 grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <div class="lg:col-span-2">
                        <div class="bg-white rounded-2xl shadow border p-6">
                            <div class="flex items-center justify-between">
                                <h2 class="text-lg font-semibold">Recursos e Tarefas</h2>
                                <div class="text-sm text-slate-500">Entrega: {{ $lesson->due_date ?? '—' }}</div>
                            </div>

                            <ul class="mt-4 space-y-3">
                                @foreach ($lesson->resources ?? [] as $res)
                                    <li class="flex items-center justify-between gap-4">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-600 font-medium">
                                                {{ strtoupper(substr($res['name'], 0, 2)) }}</div>
                                            <div>
                                                <div class="font-medium">{{ $res['name'] }}</div>
                                                <div class="text-xs text-slate-500">{{ $res['meta'] ?? '' }}</div>
                                            </div>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <a href="{{ $res['url'] }}"
                                                class="px-3 py-1 rounded-md border text-sm">Abrir</a>
                                            <a href="{{ $res['url'] }}" download
                                                class="px-3 py-1 rounded-md bg-slate-100 text-sm">Baixar</a>
                                        </div>
                                    </li>
                                @endforeach
                            </ul>

                            <!-- Q&A / Comments -->
                            <div class="mt-6">
                                <h3 class="text-sm font-semibold">Perguntas & Respostas</h3>
                                <div class="mt-3">
                                    <form class="flex gap-3" @submit.prevent="postQuestion">
                                        <input x-model="newQuestion" class="flex-1 rounded-lg border px-3 py-2"
                                            placeholder="Pergunte algo sobre a aula...">
                                        <button type="submit"
                                            class="px-4 py-2 rounded-lg bg-emerald-600 text-white">Enviar</button>
                                    </form>

                                    <template x-for="q in questions" :key="q.id">
                                        <div class="mt-3 p-3 border rounded-lg bg-slate-50">
                                            <div class="text-sm font-medium" x-text="q.user"></div>
                                            <div class="text-sm text-slate-700 mt-1" x-text="q.text"></div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SIDEBAR: Playlist, next lesson, progress -->
                    <aside class="bg-white rounded-2xl shadow border p-4 h-fit">
                        <div class="flex items-center justify-between">
                            <div>
                                <div class="text-xs text-slate-500">Curso</div>
                                <div class="font-semibold">{{ $lesson->class->course->name ?? 'Módulo' }}</div>
                                <div class="text-xs text-slate-500">{{ $lesson->class->course->total_lessons ?? '—' }}
                                    aulas
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium">Próxima Aula</h4>

                            @if ($lesson->next_lesson)
                                <a href="{{ route('lessons.show', $lesson->next_lesson->id) }}"
                                    class="mt-2 block rounded-lg p-3 bg-slate-50 border hover:bg-slate-100 transition">
                                    {{ $lesson->next_lesson->title }}
                                </a>
                            @else
                                <div class="mt-2 block rounded-lg p-3 bg-green-50 border text-green-700 font-semibold">
                                    Esta é a última aula do módulo 🎓
                                </div>
                            @endif
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium">Progresso do curso</h4>
                            <div class="mt-2">
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div style="width: {{ $lesson->course->progress ?? 0 }}%"
                                        class="h-2 rounded-full bg-gradient-to-r from-emerald-500 to-sky-500"></div>
                                </div>
                                <div class="text-xs text-slate-500 mt-2">{{ $lesson->course->progress ?? 0 }}% completo
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium">Aulas do módulo</h4>
                            <ul class="mt-2 space-y-2 max-h-52 overflow-auto pr-2">
                                @if ($lesson->class->lessons->count())
                                    <ul
                                        class="border-t border-gray-100 bg-gray-50 px-4 py-2 space-y-1 text-xs text-gray-600">
                                        @foreach ($lesson->class->lessons as $lesson)
                                            @php
                                                $watched =
                                                    $lesson->students->where('id', auth()->id())->first()?->pivot
                                                        ->watched ?? false;
                                            @endphp
                                            <li class="flex items-center gap-2">
                                                <a href="{{ route('lessons.show', $lesson->id) }}"
                                                    class="flex items-center gap-2 hover:text-[#2b2c43] transition">

                                                    @if ($watched)
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-4 h-4 text-green-500 flex-shrink-0" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    @endif

                                                    {{ $lesson->title }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    <p class="px-4 py-2 text-xs text-gray-400 bg-gray-50 border-t border-gray-100">
                                        Nenhuma aula disponível neste módulo.
                                    </p>
                                @endif
                            </ul>
                        </div>
                    </aside>
                </div>
            </main>

            <!-- RIGHT SIDEBAR (on lg screens) -->
            <div class="hidden lg:block">
                <div class="sticky top-6 space-y-6">
                    {{-- <div class="bg-white rounded-2xl shadow border p-4 w-80">
                        <h4 class="text-sm font-semibold">Seu progresso</h4>
                        <div class="mt-3">
                            <div class="text-sm font-medium">Nível atual: {{ $lesson->studentLevel ?? 'Iniciante' }}
                            </div>
                            <div class="text-xs text-slate-500 mt-1">XP: {{ $lesson->studentXp ?? 0 }}</div>
                            <div class="mt-3">
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div style="width: {{ $lesson->progressPercent ?? 0 }}%"
                                        class="h-2 rounded-full bg-emerald-500"></div>
                                </div>
                            </div>
                        </div>
                    </div> --}}

                    @if ($currentCourse)
                        <div class="bg-white rounded-2xl shadow p-6">
                            <div class="flex justify-between mb-2 text-sm font-medium">
                                <span>Curso Atual: {{ $currentCourse->name }}</span>
                                <span>{{ $currentCourse->progress }}%</span>
                            </div>
                            <div class="w-full bg-gray-200 rounded-full h-3">
                                <div class="bg-sky-500 h-3 rounded-full" style="width: {{ $currentCourse->progress }}%">
                                </div>
                            </div>
                            <div class="text-xs text-slate-500 mt-1">Pontos de XP: {{ $userPoints ?? 0 }}</div>
                            <div class="text-xs text-slate-500 mt-1">Posição no Ranking: {{ $position ?? 0 }}°</div>
                            <p class="mt-1 text-xs text-gray-500">
                                Última aula assistida: {{ $lastLesson->title }}
                            </p>
                        </div>
                    @endif

                    <div class="bg-white rounded-2xl shadow border p-4 w-80">
                        <h4 class="text-sm font-semibold">Certificado</h4>
                        <p class="text-xs text-slate-500 mt-2">Ao concluir o módulo, você receberá um certificado.</p>
                        <a href="#" class="mt-3 inline-block px-3 py-2 rounded-lg bg-sky-600 text-white text-sm">Ver
                            certificado</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
@endsection
