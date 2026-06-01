<section id="meuscursos" class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <h3 class="text-2xl font-bold text-white">Meus Cursos</h3>

        @auth
            <div class="text-sm text-gray-400">
                👋 Olá, <strong class="text-white">{{ auth()->user()->name }}</strong>!
            </div>
        @else
            <div class="text-sm text-gray-400">
                👋 Olá, visitante! <a href="{{ route('login') }}" class="text-[#82cd29] font-semibold hover:underline">
                    Faça login</a> para acompanhar seu progresso ou <a href="{{ route('login') }}"
                    class="text-[#82cd29] font-semibold hover:underline">
                    Cadastre-se</a> para se tornar nosso aluno.
            </div>
        @endauth
    </div>

    <div class="flex flex-wrap gap-3 mb-8">
        <button class="filter-btn active" data-language="all">🌐 Todos</button>
        <button class="filter-btn" data-language="en">
            <img src="https://flagcdn.com/us.svg" alt="Inglês" class="w-5 h-5 rounded-sm object-cover"> Inglês
        </button>
        <button class="filter-btn" data-language="es">
            <img src="https://flagcdn.com/es.svg" alt="Espanhol" class="w-5 h-5 rounded-sm object-cover"> Espanhol
        </button>
    </div>

    @auth
        @if ($currentCourse)
            <div class="bg-[#0b1528]/80 backdrop-blur-md rounded-2xl border border-white/5 p-5 mb-10 max-w-md shadow-2xl">
                <div class="flex justify-between items-center text-sm font-semibold mb-2">
                    <span class="text-white">{{ $currentCourse->name }}</span>
                    <span class="text-[#82cd29]">{{ $currentCourse->progress }}%</span>
                </div>
                <div class="w-full bg-white/10 rounded-full h-2">
                    <div class="bg-[#82cd29] h-2 rounded-full transition-all duration-500 shadow-sm shadow-[#82cd29]/20"
                        style="width: {{ $currentCourse->progress }}%">
                    </div>
                </div>
                <div class="text-xs text-gray-400 mt-2">
                    XP: <strong class="text-white">{{ $userPoints ?? 0 }}</strong>
                </div>
                @if ($lastLesson)
                    <p class="text-xs text-gray-400 mt-1 italic">Última aula: <span
                            class="text-gray-300">{{ $lastLesson->title }}</span></p>
                @endif
            </div>
        @endif
    @endauth

    <div id="courses-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($courses as $course)
            @php
                $student = auth()->user()?->student;
                $isLanguageMatch = $student?->language == $course->language || $student?->language == 'both';
                $hasFullAccess = auth()->check() && auth()->user()->hasFullAccess();
            @endphp

            @if ($isLanguageMatch || $hasFullAccess)
                @php
                    $isCourseLocked = false;
                    if (auth()->check()) {
                        $isCourseLocked = !$course->isReleasedForStudent($student->id);
                    }

                    $formattedTitle = Str::slug($course->name, '+');
                    // Placehold atualizado para herdar o fundo escuro do layout caso a imagem falte
                    $thumb = 'https://placehold.co/600x400/0b1528/ffffff?text=' . urlencode($course->name);
                @endphp

                <div class="course-card bg-[#0b1528] rounded-2xl border border-white/5 transition-all duration-300 {{ $isCourseLocked ? 'opacity-50 grayscale-[0.7] pointer-events-none' : 'hover:border-white/20 hover:shadow-2xl' }}"
                    data-language="{{ $course->language }}">

                    <div class="relative">
                        <img src="{{ $course->image_path ? Storage::url($course->image_path) : $thumb }}"
                            alt="{{ $course->name }}" class="w-full h-40 object-cover rounded-t-2xl">

                        @if ($isCourseLocked)
                            <div
                                class="absolute inset-0 bg-[#020916]/80 flex flex-col items-center justify-center rounded-t-2xl backdrop-blur-[3px]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-red-500 animate-pulse"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                <span class="text-red-400 font-bold text-[10px] mt-2 uppercase tracking-widest">Curso
                                    Bloqueado</span>
                            </div>
                        @endif
                    </div>

                    <div class="p-5">
                        <h4 class="font-semibold text-lg mb-2 text-white truncate">{{ $course->name }}</h4>

                        @if ($isCourseLocked)
                            <div class="bg-red-500/10 border-l-4 border-red-500 p-3 mb-4 rounded-r-xl">
                                <p class="text-[11px] text-red-300 leading-tight">
                                    <strong>🔒 Atenção:</strong> Conclua 100% do curso anterior de
                                    <u>{{ $course->language == 'en' ? 'Inglês' : 'Espanhol' }}</u> primeiro para
                                    liberar este.
                                </p>
                            </div>
                        @else
                            <p class="text-sm text-gray-400 mb-4 line-clamp-2 leading-relaxed">
                                {{ $course->description }}</p>
                        @endif

                        @auth
                            <div class="mb-4">
                                <div class="flex justify-between text-xs mb-1">
                                    <span class="text-gray-400">Progresso</span>
                                    <span class="font-semibold text-[#82cd29]">{{ $course->progress }}%</span>
                                </div>
                                <div class="w-full bg-white/10 rounded-full h-1.5">
                                    <div class="bg-[#82cd29] h-1.5 rounded-full transition-all duration-500"
                                        style="width: {{ $course->progress }}%">
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-500 mt-1.5">{{ $course->total_lessons }} aulas totais</p>
                            </div>
                        @else
                            <p class="text-xs text-gray-400 mb-4">{{ $course->total_lessons }} aulas disponíveis</p>
                        @endauth

                        <div class="space-y-3">
                            <button
                                class="toggle-modules-btn w-full text-sm font-semibold text-gray-300 hover:text-[#82cd29] transition flex items-center justify-center gap-1">
                                Ver módulos ↓
                            </button>

                            <div class="modules-list hidden space-y-2 text-sm">
                                @forelse ($course->classes as $index => $class)
                                    @php
                                        $isModuleLocked = false;
                                        $pendingLessons = collect();

                                        if (auth()->check() && !$hasFullAccess && $index > 0) {
                                            $previousClass = $course->classes[$index - 1];
                                            if (!$previousClass->isCompletedByStudent(auth()->id())) {
                                                $isModuleLocked = true;

                                                foreach ($previousClass->lessons as $l) {
                                                    if ($l->assessments->isNotEmpty()) {
                                                        $qIds = $l->assessments->flatMap->questions->pluck('id');
                                                        $ansCount = \App\Models\Answer::where('user_id', auth()->id())
                                                            ->whereIn('question_id', $qIds)
                                                            ->count();
                                                        if ($ansCount < $qIds->count()) {
                                                            $pendingLessons->push($l->title);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    @endphp

                                    <div
                                        class="rounded-xl border transition-colors {{ $isModuleLocked ? 'bg-red-500/5 border-red-500/20 opacity-80' : 'bg-white/5 border-white/5' }}">
                                        <div class="p-3 flex justify-between items-center">
                                            <span
                                                class="font-medium flex items-center gap-2 {{ $isModuleLocked ? 'text-red-400' : 'text-gray-200' }}">
                                                @if ($isModuleLocked)
                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                        class="w-4 h-4 text-red-500 shrink-0" viewBox="0 0 20 20"
                                                        fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"
                                                            clip-rule="evenodd" />
                                                    </svg>
                                                @endif
                                                {{ $class->name }}
                                            </span>
                                            <span class="text-xs text-gray-500">{{ $class->lessons->count() }}
                                                aulas</span>
                                        </div>

                                        @if ($isModuleLocked)
                                            <div class="px-3 pb-3">
                                                <div class="bg-[#020916] rounded-lg p-2 border border-red-500/20">
                                                    <p class="text-[9px] font-bold text-red-400 uppercase mb-1">
                                                        Avaliações pendentes no anterior:</p>
                                                    <ul class="space-y-0.5">
                                                        @forelse($pendingLessons->unique() as $pTitle)
                                                            <li
                                                                class="text-[10px] text-gray-400 flex items-center gap-1">
                                                                <div class="w-1 h-1 bg-red-500 rounded-full"></div>
                                                                {{ $pTitle }}
                                                            </li>
                                                        @empty
                                                            <li class="text-[10px] text-gray-500 italic">Conclua o
                                                                módulo anterior: {{ $previousClass->name }}</li>
                                                        @endforelse
                                                    </ul>
                                                </div>
                                            </div>
                                        @elseif ($class->lessons->count())
                                            <ul
                                                class="border-t border-white/5 bg-[#020916]/40 px-4 py-2 space-y-2 text-xs">
                                                @foreach ($class->lessons as $lesson)
                                                    <li class="flex items-center gap-2">
                                                        @auth
                                                            <a href="{{ route('lessons.show', $lesson->id) }}"
                                                                class="flex items-center gap-2 text-gray-300 hover:text-[#82cd29] transition py-0.5 w-full">
                                                                <span
                                                                    class="w-4 h-4 flex items-center justify-center shrink-0">
                                                                    @if ($lesson->watched_by_student ?? false)
                                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                                            class="w-4 h-4 text-[#82cd29]" fill="none"
                                                                            viewBox="0 0 24 24" stroke="currentColor"
                                                                            stroke-width="3">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                d="M5 13l4 4L19 7" />
                                                                        </svg>
                                                                    @else
                                                                        <div class="w-1.5 h-1.5 rounded-full bg-gray-600">
                                                                        </div>
                                                                    @endif
                                                                </span>
                                                                <span class="truncate">{{ $lesson->title }}</span>
                                                            </a>
                                                        @else
                                                            <div
                                                                class="flex items-center gap-2 text-gray-500 cursor-not-allowed py-0.5">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    class="w-4 h-4 text-gray-600 shrink-0" fill="none"
                                                                    viewBox="0 0 24 24" stroke="currentColor"
                                                                    stroke-width="2">
                                                                    <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                    <path
                                                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                                </svg>
                                                                <span class="truncate">{{ $lesson->title }}</span>
                                                            </div>
                                                        @endauth
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @else
                                            <p
                                                class="px-4 py-2 text-xs text-gray-500 bg-[#020916]/20 border-t border-white/5 italic">
                                                Nenhuma aula disponível.</p>
                                        @endif
                                    </div>
                                @empty
                                    <p class="text-gray-500 text-xs text-center py-2">Nenhum módulo disponível.</p>
                                @endforelse
                            </div>
                        </div>

                        <div class="mt-5 text-center">
                            @auth
                                @php
                                    $firstClass = $course->classes->first();
                                    $continueLesson =
                                        $lastLesson && $lastLesson->class->course_id === $course->id
                                            ? $lastLesson
                                            : $firstClass?->lessons?->first();
                                @endphp

                                @if ($continueLesson)
                                    <a href="{{ route('lessons.show', $continueLesson->id) }}"
                                        class="inline-block w-full bg-[#82cd29] hover:bg-[#76c022] text-[#020916] text-sm font-bold py-2.5 rounded-xl shadow-md shadow-[#82cd29]/5 transition duration-300">
                                        Continuar Curso
                                    </a>
                                @else
                                    <button
                                        class="inline-block w-full bg-white/5 text-gray-500 text-sm font-medium py-2.5 rounded-xl cursor-not-allowed border border-white/5">
                                        Sem aulas disponíveis
                                    </button>
                                @endif
                            @else
                                <a href="{{ route('login') }}"
                                    class="inline-block w-full bg-white/5 hover:bg-white/10 text-white border border-white/10 text-sm font-medium py-2.5 rounded-xl transition duration-300">
                                    Acessar curso
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>
</section>

<script>
    const filterButtons = document.querySelectorAll('.filter-btn');
    const courseCards = document.querySelectorAll('.course-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const language = button.dataset.language;
            courseCards.forEach(card => {
                const visible = language === 'all' || card.dataset.language === language;
                card.style.opacity = visible ? '1' : '0';
                card.style.transform = visible ? 'scale(1)' : 'scale(0.97)';

                if (visible) {
                    card.style.display = 'block';
                    card.style.pointerEvents = card.classList.contains('pointer-events-none') ?
                        'none' : 'auto';
                } else {
                    setTimeout(() => {
                        if (card.style.opacity === '0') card.style.display = 'none';
                    }, 400);
                    card.style.pointerEvents = 'none';
                }
            });
            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    document.querySelectorAll('.toggle-modules-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const list = btn.nextElementSibling;
            list.classList.toggle('hidden');
            btn.textContent = list.classList.contains('hidden') ? 'Ver módulos ↓' : 'Ocultar módulos ↑';
        });
    });
</script>

<style>
    .filter-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.75rem;
        font-weight: 600;
        font-size: 0.875rem;
        background-color: #0b1528;
        color: #94a3b8;
        border: 1px solid rgba(255, 255, 255, 0.05);
        transition: all 0.25s ease-in-out;
    }

    .filter-btn:hover {
        background-color: rgba(255, 255, 255, 0.05);
        color: #fff;
    }

    .filter-btn.active {
        background-color: #82cd29;
        color: #020916;
        border-color: #82cd29;
        box-shadow: 0 4px 12px rgba(130, 205, 41, 0.15);
    }

    .course-card {
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>
