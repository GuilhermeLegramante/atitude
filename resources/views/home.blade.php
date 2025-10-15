@extends('master')

@section('content')
    @include('partials.hero')


    @include('partials.courses')


    <!-- 🔥 Seção: Recomendados -->
    <section class="bg-gray-100 py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h3 class="text-2xl font-bold text-[#2b2c43] mb-6">Recomendados para você</h3>
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ([['title' => 'Listening Prático', 'thumb' => 'https://placehold.co/600x400/003f51/ffffff?text=Listening+Prático'], ['title' => 'Pronúncia Avançada', 'thumb' => 'https://placehold.co/600x400/2b2c43/ffffff?text=Pronúncia+Avançada'], ['title' => 'Expressões Idiomáticas', 'thumb' => 'https://placehold.co/600x400/004d60/ffffff?text=Expressões+Inglês'], ['title' => 'Simulados TOEFL', 'thumb' => 'https://placehold.co/600x400/035f6b/ffffff?text=Simulados+TOEFL']] as $rec)
                    <div class="bg-white rounded-2xl shadow hover:shadow-lg overflow-hidden transition">
                        <img src="{{ $rec['thumb'] }}" class="w-full h-36 object-cover">
                        <div class="p-4">
                            <h4 class="font-semibold mb-2 text-[#2b2c43]">{{ $rec['title'] }}</h4>
                            <a href="#" class="text-sky-600 font-medium text-sm hover:underline">Assistir
                                agora</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- 📈 Progresso -->
    <section class="max-w-7xl mx-auto px-4 py-12">
        <h3 class="text-2xl font-bold text-[#2b2c43] mb-6">Seu progresso</h3>
        <div class="bg-white rounded-2xl shadow p-6">
            <div class="flex justify-between mb-2 text-sm font-medium">
                <span>Curso Atual: Inglês para Iniciantes </span>
                <span>&nbsp;45%</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-3">
                <div class="bg-sky-500 h-3 rounded-full" style="width: 45%"></div>
            </div>
        </div>
    </section>
@endsection
