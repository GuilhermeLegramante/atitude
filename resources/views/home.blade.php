@extends('master')

@section('content')
    @include('partials.hero')

    @include('partials.courses')

    <!-- 🔥 Seção: Ferramentas Disponíveis -->
    <section class="bg-[#1e2030] py-12">
        <div class="max-w-7xl mx-auto px-4">
            <h3 class="text-2xl font-bold text-[#c0ff01] mb-6">Ferramentas Disponíveis</h3>
            <div class="grid sm:grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card Tradutor -->
                <div
                    class="bg-[#24253b]/95 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                    <div class="p-6 flex flex-col items-center text-center">
                        <div class="text-5xl mb-4">🌍</div>
                        <h4 class="font-bold text-lg mb-2 text-white">Tradutor Inteligente</h4>
                        <p class="text-gray-300 mb-4">Traduza palavras e textos entre Português, Inglês e Espanhol de forma
                            rápida e prática.</p>
                        <a href="{{ route('translator.index') }}"
                            class="bg-[#c0ff01] text-[#111827] font-semibold px-4 py-2 rounded-md hover:bg-[#aaff00] transition">
                            Acessar Tradutor
                        </a>
                    </div>
                </div>

                <!-- Card Textos -->
                <div
                    class="bg-[#24253b]/95 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                    <div class="p-6 flex flex-col items-center text-center">
                        <div class="text-5xl mb-4">📄</div>
                        <h4 class="font-bold text-lg mb-2 text-white">Textos</h4>
                        <p class="text-gray-300 mb-4">Acesse textos em Inglês e Espanhol, leia, aprenda vocabulário e
                            pratique suas habilidades de leitura.</p>
                        <a href="{{ route('texts.index') }}"
                            class="bg-[#c0ff01] text-[#111827] font-semibold px-4 py-2 rounded-md hover:bg-[#aaff00] transition">
                            Ver Textos
                        </a>
                    </div>
                </div>

                <!-- Card Meu Dicionário -->
                <div
                    class="bg-[#24253b]/95 backdrop-blur-lg rounded-2xl shadow-lg hover:shadow-2xl transition overflow-hidden">
                    <div class="p-6 flex flex-col items-center text-center">
                        <div class="text-5xl mb-4">📖</div>
                        <h4 class="font-bold text-lg mb-2 text-white">Meu Dicionário</h4>
                        <p class="text-gray-300 mb-4">Confira todas as palavras que você salvou, revise traduções e
                            acompanhe seu progresso de aprendizado.</p>
                        <a href="{{ route('dictionary.index') }}"
                            class="bg-[#c0ff01] text-[#111827] font-semibold px-4 py-2 rounded-md hover:bg-[#aaff00] transition">
                            Ver Dicionário
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection
