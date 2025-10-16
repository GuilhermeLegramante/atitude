<h2 class="text-2xl font-bold mb-2">{{ $assessment->name }}</h2>
<p class="text-gray-600 mb-4">{{ $assessment->description }}</p>

<form id="assessmentForm" data-id="{{ $assessment->id }}">
    @foreach ($assessment->questions as $question)
        <div class="mb-6 border-b border-gray-200 pb-4">
            <h3 class="font-semibold mb-2">{{ $loop->iteration }}. {{ $question->question_text }}</h3>

            {{-- Tipos de questão --}}
            @if ($question->questionType->type_name === 'Objetiva')
                @foreach ($question->alternatives as $alt)
                    <label class="block">
                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $alt->id }}">
                        {{ $alt->alternative_text }}
                    </label>
                @endforeach
            @elseif ($question->questionType->type_name === 'Discursiva')
                <textarea name="answers[{{ $question->id }}]" class="w-full border rounded-lg p-2" rows="3"
                    placeholder="Digite sua resposta..."></textarea>
            @endif
        </div>
    @endforeach

    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
        Enviar Avaliação
    </button>
</form>
