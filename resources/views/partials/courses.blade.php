<section id="meuscursos" class="max-w-7xl mx-auto px-4 py-12">
    <h3 class="text-2xl font-bold text-[#2b2c43] mb-6">Meus Cursos</h3>

    <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($courses as $course)
            @php
                $formattedTitle = Str::slug($course->name, '+'); // transforma "Inglês para Iniciantes" em "ingles+para+iniciantes"
                $thumb =
                    'https://placehold.co/600x400/1e3a8a/ffffff?text=' .
                    urlencode(ucwords(str_replace('+', ' ', $formattedTitle)));
            @endphp
            <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">

                <img src="{{ $thumb }}" alt="{{ $course->name }}" class="w-full h-40 object-cover">

                <div class="p-4">
                    <h4 class="font-semibold text-lg mb-2 text-[#2b2c43]">{{ $course->name }}</h4>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $course->description }}</p>

                    <div x-data="{ open: false }" class="mt-4">
                        <button @click="open = !open"
                            class="flex items-center justify-center w-full sm:w-auto gap-1 text-[#2b2c43] font-semibold text-sm hover:text-[#003f51] transition">
                            <span x-show="!open">Ver módulos ↓</span>
                            <span x-show="open">Ocultar módulos ↑</span>
                        </button>

                        <!-- Lista de módulos -->
                        <div x-show="open" x-collapse class="mt-4 space-y-3">
                            @forelse ($course->classes as $class)
                                <div
                                    class="bg-[#f5f7fa] rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition overflow-hidden">
                                    <div class="p-4 flex items-center justify-between">
                                        <p class="font-semibold text-sm text-[#2b2c43]">{{ $class->name }}</p>
                                        <span class="text-xs text-gray-500">{{ $class->lessons->count() }}
                                            aulas</span>
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
        @endforeach
    </div>
</section>
