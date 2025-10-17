@extends('master')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-[#1e2030]/90 backdrop-blur-lg rounded-2xl shadow-lg text-white">
        <h1 class="text-2xl font-semibold mb-6 text-[#c0ff01]">📖 Meu Dicionário</h1>

        @if ($words->isEmpty())
            <p class="text-gray-300">Você ainda não salvou nenhuma palavra.</p>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                @foreach ($words as $word)
                    <div class="bg-gray-800 rounded-xl p-4 shadow hover:shadow-lg transition">
                        <h2 class="font-semibold text-lg text-[#c0ff01]">{{ $word->word }}</h2>
                        <p class="text-gray-200">{{ $word->translation }}</p>
                        <p class="text-gray-400 text-sm mt-1">{{ $word->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
