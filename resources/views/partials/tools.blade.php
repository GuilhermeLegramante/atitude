<section class="bg-[#050e1c] py-16 border-t border-b border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <h3 class="text-2xl font-bold text-white mb-8 tracking-tight">
            Ferramentas Disponíveis
        </h3>

        <div class="grid sm:grid-cols-1 md:grid-cols-3 gap-6">

            <div
                class="bg-[#0b1528]/70 backdrop-blur-md rounded-2xl border border-white/5 hover:border-white/10 p-6 flex flex-col justify-between transition-all duration-300 group">
                <div class="space-y-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-[#82cd29] group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9h18" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg text-white">Tradutor Inteligente</h4>
                    <p class="text-gray-400 text-sm leading-relaxed">Traduza palavras e textos entre Português, Inglês e
                        Espanhol de forma rápida e prática.</p>
                </div>
                <div class="pt-6">
                    <a href="{{ route('translator.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-bold text-[#82cd29] hover:text-[#76c022] transition">
                        <span>Acessar Tradutor</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <div
                class="bg-[#0b1528]/70 backdrop-blur-md rounded-2xl border border-white/5 hover:border-white/10 p-6 flex flex-col justify-between transition-all duration-300 group">
                <div class="space-y-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-[#82cd29] group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg text-white">Textos</h4>
                    <p class="text-gray-400 text-sm leading-relaxed">Acesse textos em Inglês e Espanhol, leia, aprenda
                        vocabulário e pratique suas habilidades de leitura.</p>
                </div>
                <div class="pt-6">
                    <a href="{{ route('texts.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-bold text-[#82cd29] hover:text-[#76c022] transition">
                        <span>Ver Textos</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

            <div
                class="bg-[#0b1528]/70 backdrop-blur-md rounded-2xl border border-white/5 hover:border-white/10 p-6 flex flex-col justify-between transition-all duration-300 group">
                <div class="space-y-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-blue-500/10 flex items-center justify-center text-[#82cd29] group-hover:scale-110 transition duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h4 class="font-bold text-lg text-white">Meu Dicionário</h4>
                    <p class="text-gray-400 text-sm leading-relaxed">Confira todas as palavras que você salvou, revise
                        traduções e acompanhe seu progresso de aprendizado.</p>
                </div>
                <div class="pt-6">
                    <a href="{{ route('dictionary.index') }}"
                        class="inline-flex items-center gap-2 text-sm font-bold text-[#82cd29] hover:text-[#76c022] transition">
                        <span>Ver Dicionário</span>
                        <span>→</span>
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
