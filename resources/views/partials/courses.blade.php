<section id="meuscursos" class="max-w-7xl mx-auto px-4 py-12">
    <h3 class="text-2xl font-bold text-[#2b2c43] mb-6">Meus Cursos</h3>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8 gap-4">
        <!-- 🔹 Filtros -->
        <div class="flex items-center gap-4 flex-wrap">
            <button class="filter-btn" data-language="all">🌐 Todos</button>
            <button class="filter-btn" data-language="en">
                <img src="https://flagcdn.com/us.svg" alt="Inglês" class="w-5 h-5 rounded-sm"> Inglês
            </button>
            <button class="filter-btn" data-language="es">
                <img src="https://flagcdn.com/es.svg" alt="Espanhol" class="w-5 h-5 rounded-sm"> Espanhol
            </button>
        </div>

        <!-- 🔹 Progresso -->
        {{-- <div class="mt-4 md:mt-0 md:w-1/3">
            <div class="bg-white rounded-2xl shadow p-4">
                <div class="flex justify-between mb-2 text-sm font-medium">
                    <span>Curso Atual: Inglês para Iniciantes</span>
                    <span>45%</span>
                </div>
                <div class="w-full bg-gray-200 rounded-full h-3">
                    <div class="bg-sky-500 h-3 rounded-full" style="width: 45%"></div>
                </div>
            </div>
        </div> --}}
        <div class="bg-white rounded-2xl shadow border p-4 w-80">
            <h4 class="text-sm font-semibold">Seu progresso</h4>
            <div class="mt-3">
                <div class="text-sm font-medium">Curso atual: Getting Started
                </div>
                <div class="text-xs text-slate-500 mt-1">XP: {{ $lesson->studentXp ?? 120 }}</div>
                <div class="mt-3">
                    <div class="w-full bg-slate-100 rounded-full h-2 overflow-hidden">
                        <div style="width: {{ $lesson->progressPercent ?? 45 }}%"
                            class="h-2 rounded-full bg-emerald-500"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- 🔹 Grid de cursos -->
    <div id="courses-grid" class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6 transition-all duration-500">
        @foreach ($courses as $course)
            @php
                $formattedTitle = Str::slug($course->name, '+');
                $thumb =
                    'https://placehold.co/600x400/2b2c43/ffffff?text=' .
                    urlencode(ucwords(str_replace('+', ' ', $formattedTitle)));
            @endphp

            <div class="course-card bg-white rounded-2xl shadow hover:shadow-lg transition transform duration-300 opacity-100"
                data-language="{{ $course->language }}">

                <img src="{{ $thumb }}" alt="{{ $course->name }}" class="w-full h-40 object-cover">

                <div class="p-4">
                    <h4 class="font-semibold text-lg mb-2 text-[#2b2c43]">{{ $course->name }}</h4>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $course->description }}</p>

                    <div class="mt-4">
                        <!-- Botão ver módulos -->
                        <button
                            class="toggle-modules-btn flex items-center justify-center w-full sm:w-auto gap-1 text-[#2b2c43] font-semibold text-sm hover:text-[#003f51] transition">
                            Ver módulos ↓
                        </button>

                        <!-- Lista de módulos -->
                        <div class="modules-list mt-4 space-y-3 hidden">
                            @forelse ($course->classes as $class)
                                <div
                                    class="bg-[#f5f7fa] rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden">
                                    <div class="p-4 flex items-center justify-between">
                                        <p class="font-semibold text-sm text-[#2b2c43]">{{ $class->name }}</p>
                                        <span class="text-xs text-gray-500">{{ $class->lessons->count() }} aulas</span>
                                    </div>

                                    @if ($class->lessons->count())
                                        <ul
                                            class="border-t border-gray-100 bg-gray-50 px-4 py-2 space-y-1 text-xs text-gray-600">
                                            @foreach ($class->lessons as $lesson)
                                                <li>
                                                    <a href="{{ route('lessons.show', $lesson->id) }}"
                                                        class="flex items-center gap-2 hover:text-[#2b2c43] transition">
                                                        <svg xmlns="http://www.w3.org/2000/svg"
                                                            class="w-3.5 h-3.5 text-[#2b2c43]" viewBox="0 0 20 20"
                                                            fill="currentColor">
                                                            <path
                                                                d="M2 4.5A1.5 1.5 0 013.5 3h13A1.5 1.5 0 0118 4.5v11a1.5 1.5 0 01-2.3 1.2l-4.7-3.1a1 1 0 00-1.1 0l-4.7 3.1A1.5 1.5 0 012 15.5v-11z" />
                                                        </svg>
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
                                </div>
                            @empty
                                <p class="text-gray-400 text-xs text-center">Nenhum módulo disponível.</p>
                            @endforelse
                        </div>

                        <div class="mt-4 text-center sm:text-left">
                            <a href="{{ route('lessons.show', $course->id) }}"
                                class="inline-block bg-[#2b2c43] hover:bg-[#003f51] text-white text-sm font-medium px-6 py-2 rounded-lg shadow-md transition">
                                Continuar
                            </a>
                        </div>
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
                if (language === 'all' || card.dataset.language === language) {
                    card.style.opacity = '1';
                    card.style.transform = 'scale(1)';
                    card.style.pointerEvents = 'auto';
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    card.style.pointerEvents = 'none';
                }
            });

            // Botão ativo
            filterButtons.forEach(btn => btn.classList.remove('bg-[#2b2c43]', 'text-white'));
            button.classList.add('bg-[#2b2c43]', 'text-white');
        });
    });

    // 🔹 Toggle módulos
    const toggleButtons = document.querySelectorAll('.toggle-modules-btn');
    toggleButtons.forEach(btn => {
        btn.addEventListener('click', () => {
            const modulesList = btn.nextElementSibling;
            modulesList.classList.toggle('hidden');
            btn.textContent = modulesList.classList.contains('hidden') ? 'Ver módulos ↓' :
                'Ocultar módulos ↑';
        });
    });
</script>

<style>
    .filter-btn {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1rem;
        border-radius: 0.5rem;
        font-weight: 600;
        background-color: #e5e7eb;
        /* gray-200 */
        color: #374151;
        /* gray-700 */
        transition: all 0.3s;
        cursor: pointer;
    }

    .filter-btn:hover {
        background-color: #2b2c43;
        color: white;
    }

    /* Transição suave dos cards */
    .course-card {
        transition: all 0.4s ease;
    }
</style>
