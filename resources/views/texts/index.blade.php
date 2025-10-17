@extends('master')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-[#1e2030]/90 backdrop-blur-lg rounded-2xl shadow-lg text-white">
        <h1 class="text-2xl font-semibold mb-6 text-[#c0ff01] text-center">📚 Textos Disponíveis</h1>

        @if ($texts->count())
            <ul class="space-y-4">
                @foreach ($texts as $text)
                    <li class="p-4 bg-gray-800/50 rounded-md hover:bg-gray-800/70 transition">
                        <a href="{{ route('texts.show', $text) }}" class="flex justify-between items-center">
                            <span class="font-semibold">{{ $text->title }}</span>
                            <span class="text-sm text-gray-400">{{ $text->language == 'en' ? 'Inglês' : 'Espanhol' }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>

            <div class="mt-6">
                {{ $texts->links() }} <!-- paginação -->
            </div>
        @else
            <p class="text-center text-gray-400">Nenhum texto disponível no momento.</p>
        @endif
    </div>
@endsection
