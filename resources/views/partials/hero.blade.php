<section class="relative bg-[#020916] overflow-hidden py-16 lg:py-24 flex items-center min-h-[85vh]">

    <div class="absolute inset-0 z-0 opacity-15 pointer-events-none mix-blend-screen bg-center bg-no-repeat bg-contain"
        style="background-image: url('{{ asset('img/world-map.svg') }}');">
    </div>

    <div
        class="absolute inset-0 z-0 bg-gradient-to-t from-[#020916] via-transparent to-[#020916]/60 pointer-events-none">
    </div>
    <div class="absolute inset-0 z-0 bg-gradient-to-r from-[#020916] via-transparent to-[#020916] pointer-events-none">
    </div>

    <div
        class="absolute top-1/3 left-1/4 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[300px] bg-blue-500/10 blur-[120px] rounded-full pointer-events-none">
    </div>
    <div
        class="absolute top-1/2 right-1/4 translate-x-1/2 -translate-y-1/2 w-[500px] h-[400px] bg-[#82cd29]/5 blur-[150px] rounded-full pointer-events-none">
    </div>

    <div class="relative z-10 max-w-7xl mx-auto px-4 w-full grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">

        <div class="flex flex-col justify-center text-left space-y-6">
            <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold text-white tracking-tight leading-tight">
                Fale com o mundo.<br>
                <span class="text-[#82cd29]">Evolua sem limites.</span>
            </h1>

            <p class="text-base sm:text-lg text-gray-400 max-w-xl leading-relaxed">
                Domine o inglês, espanhol e muito mais com professores e recursos modernos.
                Seu progresso é visível em tempo real.
            </p>

            <div class="pt-2">
                <a href="#meuscursos"
                    class="inline-flex items-center gap-3 bg-[#82cd29] hover:bg-[#76c022] text-[#020916] font-bold px-8 py-4 rounded-2xl transition-all duration-200 transform hover:-translate-y-0.5 shadow-lg shadow-[#82cd29]/20 group">
                    <span>Acessar minhas aulas</span>
                    <svg class="w-5 h-5 transform group-hover:translate-x-1 transition-transform" fill="none"
                        stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>

        <div class="flex flex-col items-center lg:items-end relative w-full mt-8 lg:mt-0">

            <div class="mb-6 text-center lg:text-right max-w-xs lg:mr-6">
                <p class="text-gray-300 text-lg sm:text-xl leading-snug">
                    Seja muito <span
                        class="text-[#82cd29] font-semibold italic block sm:inline lg:block text-2xl">bem-vindo!</span>
                    <span class="text-gray-400 text-sm block mt-1">Atitude te leva mais longe.</span>
                </p>
            </div>

            <div class="relative group max-w-full">
                <div
                    class="absolute -inset-1 bg-gradient-to-r from-[#82cd29]/30 to-blue-500/20 rounded-[2.5rem] blur-xl opacity-75 group-hover:opacity-100 transition duration-700 pointer-events-none">
                </div>

                <div
                    class="absolute -inset-[1px] bg-gradient-to-tr from-white/10 via-transparent to-white/5 rounded-[2.4rem] pointer-events-none z-10">
                </div>

                <img src="{{ asset('img/aluno.png') }}" alt="Estudo Atitude"
                    class="relative w-full max-w-[32rem] sm:max-w-[32rem] h-auto object-cover rounded-[2.3rem] shadow-2xl z-0 block">
            </div>

        </div>
    </div>
</section>
