@extends('master')

@section('content')
    @include('partials.hero')

    <!-- 🎯 Seção: Meus Cursos -->
    {{-- <section id="meuscursos" class="max-w-7xl mx-auto px-4 py-12">
        <h3 class="text-2xl font-bold text-[#2b2c43] mb-6">Meus Cursos</h3>
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach ([
        ['title' => 'Inglês para Iniciantes', 'desc' => 'Aprenda o básico do inglês com aulas práticas e interativas.', 'thumb' => 'https://placehold.co/600x400/1e3a8a/ffffff?text=Inglês+Iniciantes'],
        ['title' => 'Conversação em Espanhol', 'desc' => 'Desenvolva sua fala e compreensão com diálogos reais.', 'thumb' => 'https://placehold.co/600x400/0369a1/ffffff?text=Espanhol+Conversação'],
        ['title' => 'Francês do Zero', 'desc' => 'Aprenda a língua e a cultura francesa passo a passo.', 'thumb' => 'https://placehold.co/600x400/0f766e/ffffff?text=Francês+do+Zero'],
        ['title' => 'Gramática Inglesa Avançada', 'desc' => 'Aprofunde-se nas regras e estruturas mais complexas.', 'thumb' => 'https://placehold.co/600x400/2563eb/ffffff?text=Gramática+Inglesa'],
        ['title' => 'Vocabulário do Dia a Dia', 'desc' => 'Amplie seu vocabulário com expressões úteis e modernas.', 'thumb' => 'https://placehold.co/600x400/0ea5e9/ffffff?text=Vocabulário'],
        ['title' => 'Inglês para Viagens', 'desc' => 'Comunique-se com confiança em qualquer destino.', 'thumb' => 'https://placehold.co/600x400/0284c7/ffffff?text=Inglês+para+Viagens'],
    ] as $course)
                <div class="bg-white rounded-2xl shadow hover:shadow-lg transition overflow-hidden">
                    <img src="{{ $course['thumb'] }}" alt="{{ $course['title'] }}" class="w-full h-40 object-cover">
                    <div class="p-4">
                        <h4 class="font-semibold text-lg mb-2">{{ $course['title'] }}</h4>
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ $course['desc'] }}</p>
                        <a href="{{ route('lessons.show', 1) }}"
                            class="inline-block bg-[#2b2c43] hover:bg-[#003f51] text-white text-sm font-medium px-4 py-2 rounded-lg transition">Continuar</a>
                    </div>
                </div>
            @endforeach
        </div>
    </section> --}}

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
