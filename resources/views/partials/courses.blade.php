<section id="meuscursos" class="max-w-7xl mx-auto px-4 py-12">
    <div class="flex items-center justify-between mb-8 flex-wrap gap-4">
        <h3 class="text-2xl font-bold text-[#2b2c43]">Meus Cursos</h3>

        @auth
            <div class="text-sm text-gray-600">
                👋 Olá, <strong>{{ auth()->user()->name }}</strong>!
            </div>
        @else
            <div class="text-sm text-gray-600">
                👋 Olá, visitante! <a href="{{ route('login') }}" class="text-sky-600 font-semibold hover:underline">
                    Faça login</a> para acompanhar seu progresso ou cadastre-se para se tornar nosso aluno.
            </div>
        @endauth
    </div>

    <!-- 🔹 Filtros -->
    <div class="flex flex-wrap gap-3 mb-8">
        <button class="filter-btn active" data-language="all">🌐 Todos</button>
        <button class="filter-btn" data-language="en">
            <img src="https://flagcdn.com/us.svg" alt="Inglês" class="w-5 h-5 rounded-sm"> Inglês
        </button>
        <button class="filter-btn" data-language="es">
            <img src="https://flagcdn.com/es.svg" alt="Espanhol" class="w-5 h-5 rounded-sm"> Espanhol
        </button>
    </div>

    <!-- 🔹 Progresso e status do aluno -->
    @auth
        @if ($currentCourse)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-10 max-w-md">
                <div class="flex justify-between items-center text-sm font-semibold mb-1">
                    <span>{{ $currentCourse->name }}</span>
                    <span>{{ $currentCourse->progress }}%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-sky-500 h-2 rounded-full transition-all duration-500"
                        style="width: {{ $currentCourse->progress }}%">
                    </div>
                </div>
                <div class="text-xs text-gray-500 mt-2">
                    XP: <strong>{{ $userPoints ?? 0 }}</strong> • Ranking: <strong>{{ $position ?? 0 }}°</strong>
                </div>
                @if ($lastLesson)
                    <p class="text-xs text-gray-500 mt-1 italic">Última aula: {{ $lastLesson->title }}</p>
                @endif
            </div>
        @endif
    @endauth

    <!-- 🔹 Grid de cursos -->
    <div id="courses-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($courses as $course)
            @php
                $formattedTitle = Str::slug($course->name, '+');
                $thumb =
                    'https://placehold.co/600x400/2b2c43/ffffff?text=' .
                    urlencode(ucwords(str_replace('+', ' ', $formattedTitle)));
            @endphp

            <div class="course-card bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300"
                data-language="{{ $course->language }}">

                <img src="{{ $thumb }}" alt="{{ $course->name }}"
                    class="w-full h-40 object-cover rounded-t-2xl">

                <div class="p-5">
                    <h4 class="font-semibold text-lg mb-1 text-[#2b2c43] truncate">{{ $course->name }}</h4>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $course->description }}</p>

                    @auth
                        <div class="bg-gray-200 rounded-full h-2 mb-1">
                            <div class="bg-green-500 h-2 rounded-full transition-all duration-500"
                                style="width: {{ $course->progress }}%">
                            </div>
                        </div>
                        <p class="text-xs text-gray-500 mb-3">{{ $course->progress }}% concluído •
                            {{ $course->total_lessons }} aulas</p>
                    @else
                        <p class="text-xs text-gray-500 mb-3">{{ $course->total_lessons }} aulas disponíveis</p>
                    @endauth

                    <div class="space-y-3">
                        <button
                            class="toggle-modules-btn w-full text-sm font-semibold text-[#2b2c43] hover:text-sky-600 transition">
                            Ver módulos ↓
                        </button>

                        <div class="modules-list hidden space-y-2 text-sm">
                            @forelse ($course->classes as $class)
                                <div class="bg-gray-50 rounded-xl border border-gray-100 overflow-hidden">
                                    <div class="p-3 flex justify-between items-center">
                                        <span class="font-medium">{{ $class->name }}</span>
                                        <span class="text-xs text-gray-500">{{ $class->lessons->count() }} aulas</span>
                                    </div>

                                    @if ($class->lessons->count())
                                        <ul class="border-t border-gray-100 bg-white px-4 py-2 space-y-1 text-xs">
                                            @foreach ($class->lessons as $lesson)
                                                <li class="flex items-center gap-2">
                                                    @auth
                                                        <a href="{{ route('lessons.show', $lesson->id) }}"
                                                            class="flex items-center gap-2 hover:text-sky-600 transition">

                                                            {{-- Ícone (ou espaço vazio) --}}
                                                            <span class="w-4 h-4 flex items-center justify-center">
                                                                @if ($lesson->watched_by_student ?? false)
                                                                    <svg xmlns="http://www.w3.org/2000/svg"
                                                                        class="w-4 h-4 text-green-500 flex-shrink-0"
                                                                        fill="none" viewBox="0 0 24 24"
                                                                        stroke="currentColor" stroke-width="3">
                                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                                            d="M5 13l4 4L19 7" />
                                                                    </svg>
                                                                @endif
                                                            </span>

                                                            {{ $lesson->title }}
                                                        </a>
                                                    @else
                                                        <div class="flex items-center gap-2 text-gray-500 cursor-not-allowed"
                                                            title="Faça login para acessar">
                                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                                class="w-4 h-4 text-gray-400 flex-shrink-0" fill="none"
                                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                            </svg>
                                                            {{ $lesson->title }}
                                                        </div>
                                                    @endauth
                                                </li>
                                            @endforeach

                                        </ul>
                                    @else
                                        <p
                                            class="px-4 py-2 text-xs text-gray-400 bg-gray-50 border-t border-gray-100 italic">
                                            Nenhuma aula disponível.
                                        </p>
                                    @endif
                                </div>
                            @empty
                                <p class="text-gray-400 text-xs text-center">Nenhum módulo disponível.</p>
                            @endforelse
                        </div>
                    </div>

                    <div class="mt-4 text-center">
                        @auth
                            <a href="{{ route('lessons.show', $course->id) }}"
                                class="inline-block bg-[#2b2c43] hover:bg-[#003f51] text-white text-sm font-medium px-6 py-2 rounded-lg shadow-sm transition">
                                Continuar
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                                class="inline-block bg-sky-600 hover:bg-sky-700 text-white text-sm font-medium px-6 py-2 rounded-lg shadow-sm transition">
                                Acessar curso
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</section>

<script>
    // 🔹 Filtro com animação
    const filterButtons = document.querySelectorAll('.filter-btn');
    const courseCards = document.querySelectorAll('.course-card');

    filterButtons.forEach(button => {
        button.addEventListener('click', () => {
            const language = button.dataset.language;

            courseCards.forEach(card => {
                const visible = language === 'all' || card.dataset.language === language;
                card.style.opacity = visible ? '1' : '0';
                card.style.transform = visible ? 'scale(1)' : 'scale(0.97)';
                card.style.pointerEvents = visible ? 'auto' : 'none';
            });

            filterButtons.forEach(btn => btn.classList.remove('active'));
            button.classList.add('active');
        });
    });

    // 🔹 Toggle módulos
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
        gap: 0.4rem;
        padding: 0.4rem 0.9rem;
        border-radius: 0.6rem;
        font-weight: 600;
        font-size: 0.9rem;
        background-color: #f1f5f9;
        color: #374151;
        border: 1px solid transparent;
        transition: all 0.25s ease;
    }

    .filter-btn:hover {
        background-color: #e2e8f0;
    }

    .filter-btn.active {
        background-color: #2b2c43;
        color: #fff;
        border-color: #2b2c43;
    }

    .course-card {
        transition: all 0.4s ease;
    }
</style>
