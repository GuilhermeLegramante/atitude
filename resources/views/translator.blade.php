@extends('master')

@section('content')
    <div class="max-w-4xl mx-auto mt-10 p-6 bg-[#1e2030]/90 backdrop-blur-lg rounded-2xl shadow-lg text-white">
        <h1 class="text-2xl font-semibold mb-6 text-center text-[#c0ff01]">🌍 Tradutor Inteligente</h1>

        @if ($errors->any())
            <div class="bg-red-600/80 p-3 rounded-md mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('translator.translate') }}" class="space-y-4">
            @csrf

            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="block mb-1 font-medium">Idioma de Origem</label>
                    <select name="source" class="w-full bg-gray-800 rounded-md p-2 focus:ring-2 focus:ring-[#c0ff01]">
                        <option value="auto" {{ isset($source) && $source == 'auto' ? 'selected' : '' }}>Detectar
                            automaticamente</option>
                        <option value="pt" {{ isset($source) && $source == 'pt' ? 'selected' : '' }}>Português
                        </option>
                        <option value="en" {{ isset($source) && $source == 'en' ? 'selected' : '' }}>Inglês</option>
                        <option value="es" {{ isset($source) && $source == 'es' ? 'selected' : '' }}>Espanhol</option>
                    </select>
                </div>

                <div class="flex-1">
                    <label class="block mb-1 font-medium">Idioma de Destino</label>
                    <select name="target" class="w-full bg-gray-800 rounded-md p-2 focus:ring-2 focus:ring-[#c0ff01]">
                        <option value="pt" {{ isset($target) && $target == 'pt' ? 'selected' : '' }}>Português
                        </option>
                        <option value="en" {{ isset($target) && $target == 'en' ? 'selected' : '' }}>Inglês</option>
                        <option value="es" {{ isset($target) && $target == 'es' ? 'selected' : '' }}>Espanhol</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block mb-1 font-medium">Texto para traduzir</label>
                <textarea name="text" rows="5" class="w-full bg-gray-800 rounded-md p-3 focus:ring-2 focus:ring-[#c0ff01]"
                    placeholder="Digite o texto...">{{ $originalText ?? '' }}</textarea>
            </div>

            <button type="submit"
                class="bg-[#c0ff01] text-[#111827] font-semibold px-6 py-2 rounded-md hover:bg-[#aaff00] transition">
                Traduzir
            </button>
        </form>

        @isset($translatedText)
            <div class="mt-6 p-4 bg-gray-900 rounded-md">
                <h2 class="font-semibold mb-2 text-[#c0ff01]">Tradução:</h2>
                <p class="text-lg leading-relaxed">{{ $translatedText }}</p>
            </div>
        @endisset
    </div>
@endsection
