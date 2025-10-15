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
                        <iframe class="absolute top-0 left-0 w-full h-full"
                            src="{{ Str::contains($lesson->video_url, 'youtube.com') || Str::contains($lesson->video_url, 'youtu.be')
                                ? preg_replace(['/youtu\\.be\\//', '/watch\\?v=/', '/\\&.*/'], ['embed/', 'embed/', ''], $lesson->video_url)
                                : $lesson->video_url }}"
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
                                    {{ $lesson->subtitle ?? ($lesson->description ? Str::limit($lesson->description, 140) : '') }}
                                </p>

                                <div class="mt-4 flex items-center gap-4 text-sm text-slate-600">
                                    <div class="flex items-center gap-3">
                                        <img src="{{ $lesson->teacher->avatar ?? asset('img/avatar-placeholder.png') }}"
                                            alt="Professor" class="w-9 h-9 rounded-full object-cover">
                                        <div>
                                            <div class="font-medium">{{ $lesson->teacher->name ?? 'Professor' }}</div>
                                            <div class="text-xs text-slate-500">{{ $lesson->duration ?? '—' }}</div>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2">
                                        <progress value="{{ $lesson->progressPercent ?? 0 }}" max="100"
                                            class="w-40 h-2"></progress>
                                        <div class="text-xs">{{ $lesson->progressPercent ?? 0 }}% concluído</div>
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
                                <div class="text-xs text-slate-500">Aula</div>
                                <div class="font-semibold">{{ $lesson->module->title ?? 'Módulo' }}</div>
                                <div class="text-xs text-slate-500">{{ $lesson->module->lessons_count ?? '—' }} aulas
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium">Próxima</h4>
                            <a href="{{ $lesson->next_url ?? '#' }}"
                                class="mt-2 block rounded-lg p-3 bg-slate-50 border">{{ $lesson->next_title ?? 'Próxima aula' }}</a>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium">Progresso do curso</h4>
                            <div class="mt-2">
                                <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                                    <div style="width: {{ $lesson->courseProgress ?? 0 }}%"
                                        class="h-2 rounded-full bg-gradient-to-r from-emerald-500 to-sky-500"></div>
                                </div>
                                <div class="text-xs text-slate-500 mt-2">{{ $lesson->courseProgress ?? 0 }}% completo
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <h4 class="text-sm font-medium">Aulas do módulo</h4>
                            <ul class="mt-2 space-y-2 max-h-52 overflow-auto pr-2">
                                @foreach ($lesson->module->lessons ?? [] as $li)
                                    <li class="flex items-center gap-3">
                                        <a href="{{ $li->url }}" class="flex items-center gap-3 w-full">
                                            <img src="{{ $li->thumb ?? asset('img/atitude_logo_contorno.png') }}"
                                                class="w-12 h-8 rounded-md object-cover">
                                            <div class="flex-1 text-sm truncate">{{ $li->title }}</div>
                                            <div class="text-xs text-slate-500">{{ $li->duration ?? '' }}</div>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </aside>
                </div>

                {{-- <!-- RECOMMENDED -->
                <div class="mt-6">
                    <h3 class="text-lg font-semibold mb-4">Aulas recomendadas</h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach ($lesson->recommended ?? [] as $rec)
                            <a href="{{ $rec->url }}"
                                class="block bg-white rounded-lg shadow hover:shadow-md overflow-hidden">
                                <div class="aspect-w-16 aspect-h-9 bg-slate-200">
                                    <img src="{{ $rec->thumb ?? asset('img/thumb-placeholder.png') }}" alt=""
                                        class="w-full h-full object-cover">
                                </div>
                                <div class="p-3">
                                    <div class="font-medium text-sm truncate">{{ $rec->title }}</div>
                                    <div class="text-xs text-slate-500 mt-1">{{ $rec->module->title ?? '' }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div> --}}
            </main>

            <!-- RIGHT SIDEBAR (on lg screens) -->
            <div class="hidden lg:block">
                <div class="sticky top-6 space-y-6">
                    <div class="bg-white rounded-2xl shadow border p-4 w-80">
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
                    </div>

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
