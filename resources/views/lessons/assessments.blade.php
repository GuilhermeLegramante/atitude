@if ($lesson->assessments->count())
    <div class="mt-8">
        <h3 class="text-lg font-semibold text-gray-800 mb-3">Atividades Avaliativas</h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach ($lesson->assessments as $assessment)
                <div x-data="{ open: false }"
                    class="bg-white border rounded-xl shadow hover:shadow-md transition relative">

                    <div class="p-4">
                        <h4 class="font-semibold text-gray-800">{{ $assessment->name }}</h4>
                        <p class="text-sm text-gray-500 mt-1 line-clamp-2">
                            {{ $assessment->description }}
                        </p>

                        <button @click="open = true"
                            class="mt-3 inline-block bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                            Abrir Avaliação
                        </button>
                    </div>

                    <!-- Modal -->
                    <div x-show="open" x-transition x-cloak
                        class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50 p-4">

                        <div @click.away="open = false"
                            class="bg-white rounded-2xl shadow-xl max-w-2xl w-full p-6 relative overflow-y-auto max-h-[90vh]">

                            <!-- Botão de fechar -->
                            <button @click="open = false"
                                class="absolute top-3 right-3 text-gray-400 hover:text-gray-600 text-xl leading-none">
                                &times;
                            </button>

                            <h2 class="text-xl font-semibold mb-4 text-gray-800">
                                {{ $assessment->name }}
                            </h2>

                            <p class="text-gray-600 mb-4">{{ $assessment->description }}</p>

                            <!-- Conteúdo da avaliação -->
                            @if ($assessment->questions->count())
                                <div class="space-y-6">
                                    @foreach ($assessment->questions as $index => $question)
                                        <div class="border-b pb-4">
                                            <h3 class="font-medium text-gray-800">
                                                {{ $index + 1 }}. {{ $question->question_text }}
                                            </h3>

                                            @if ($question->alternatives->count())
                                                <ul class="mt-2 space-y-1">
                                                    @foreach ($question->alternatives as $alt)
                                                        <li class="flex items-center">
                                                            <input type="radio" name="question_{{ $question->id }}"
                                                                id="alt_{{ $alt->id }}"
                                                                class="text-emerald-600 focus:ring-emerald-500">
                                                            <label for="alt_{{ $alt->id }}"
                                                                class="ml-2 text-sm text-gray-700">
                                                                {{ $alt->alternative_text }}
                                                            </label>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <p class="text-gray-500 text-sm">Nenhuma questão disponível para esta
                                    avaliação.</p>
                            @endif

                            <div class="mt-6 flex justify-end">
                                <button @click="open = false"
                                    class="px-4 py-2 rounded-lg border text-gray-600 hover:bg-gray-50">
                                    Fechar
                                </button>
                                <button
                                    class="ml-2 px-4 py-2 rounded-lg bg-emerald-600 text-white hover:bg-emerald-700">
                                    Enviar Respostas
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif
