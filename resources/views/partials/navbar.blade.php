@php
    use Carbon\Carbon;
    use App\Models\LiveClass;

    // Definição do fuso horário brasileiro para evitar bugs caso o servidor esteja na nuvem
    $timezone = 'America/Sao_Paulo';
    $now = Carbon::now($timezone);

    // Simplifica a extração do dia da semana em português (ex: "segunda")
    $today = str_replace('-feira', '', $now->locale('pt_BR')->dayName);

    $student = auth()->user()?->student;

    // Busca as aulas ativas de forma condicional conforme o idioma do aluno
    $rawLiveClasses = LiveClass::where('active', true)
        ->when($student && $student->language !== 'both', function ($query) use ($student) {
            $query->where('language', $student->language);
        })
        ->ordered()
        ->get();

    // OTIMIZAÇÃO: Processa a lógica de horários e status "AO VIVO" apenas uma vez aqui no topo
    $liveClasses = $rawLiveClasses->map(function ($class) use ($now, $today, $timezone) {
        // Converte a string de hora do banco em um objeto Carbon ajustado para o fuso e dia atual
        $start = Carbon::createFromFormat('H:i:s', $class->time, $timezone)->today();
        $end = (clone $start)->addHour();

        $class->is_live = $class->weekday === $today && $now->between($start, $end);
        $class->formatted_start = $start->format('H\hi');

        return $class;
    });

    // Verifica se existe alguma aula ocorrendo agora para ativar o alerta no botão principal
    $hasLiveNow = $liveClasses->contains('is_live', true);
@endphp

<nav x-data="{ open: false, scrolled: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8);
$watch('open', value => document.body.classList.toggle('overflow-hidden', value));"
    :class="scrolled ? 'shadow-2xl bg-[#020916]/90 backdrop-blur-md border-b border-white/5' : 'bg-[#020916]'"
    class="fixed top-0 left-0 w-full z-50 text-white transition-all duration-300 ease-in-out">

    <div class="max-w-[1400px] mx-auto flex items-center justify-between px-8 py-4">

        <div class="flex items-center shrink-0">
            <a href="{{ route('home') }}" class="group">
                <img src="{{ asset('img/atitude_logo_contorno.png') }}"
                    class="w-40 transition duration-300 group-hover:scale-105" alt="Logo">
            </a>
        </div>

        <div class="hidden md:flex flex-1 justify-center">
            <div class="flex items-center gap-10 text-sm font-medium tracking-wide">

                @foreach ([['label' => 'Início', 'route' => 'home'], ['label' => 'Meus Cursos', 'route' => 'home'], ['label' => 'Tradutor', 'route' => 'translator.index'], ['label' => 'Textos', 'route' => 'texts.index'], ['label' => 'Meu Dicionário', 'route' => 'dictionary.index']] as $item)
                    @php $isCurrentRoute = request()->routeIs($item['route']); @endphp

                    <a href="{{ route($item['route']) }}"
                        class="group relative whitespace-nowrap transition duration-300 {{ $isCurrentRoute ? 'text-[#82cd29]' : 'text-gray-300 hover:text-white' }}">

                        {{ $item['label'] }}

                        <span
                            class="absolute -bottom-2 left-0 h-[2px] bg-[#82cd29] transition-all duration-300 {{ $isCurrentRoute ? 'w-full' : 'w-0 group-hover:w-full' }}"></span>
                    </a>
                @endforeach

                @auth
                    <div class="relative group">
                        <button
                            class="flex items-center gap-2 whitespace-nowrap text-gray-300 hover:text-white transition duration-300">
                            Aulas
                            @if ($hasLiveNow)
                                <span
                                    class="text-xs bg-red-600 px-2 py-0.5 rounded-full animate-pulse text-white font-bold">AO
                                    VIVO</span>
                            @endif
                        </button>

                        <div
                            class="absolute left-1/2 -translate-x-1/2 mt-6 w-[420px] p-6 bg-[#0b1528]/95 backdrop-blur-xl border border-white/10 rounded-2xl shadow-2xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300">

                            @forelse($liveClasses as $class)
                                <a href="{{ $class->link }}" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-xl transition {{ $class->is_live ? 'bg-red-600/20 border border-red-500/30' : 'hover:bg-white/5' }}">

                                    <img src="{{ $class->language === 'es' ? 'https://flagcdn.com/w40/es.png' : 'https://flagcdn.com/w40/us.png' }}"
                                        class="rounded-md shadow-md h-auto w-7 object-cover" alt="Idioma">

                                    <div class="flex-1">
                                        <p class="font-semibold text-white flex items-center gap-2">
                                            {{ $class->description ?? 'Aula ao Vivo' }}
                                            @if ($class->is_live)
                                                <span
                                                    class="text-[10px] bg-red-600 px-2 py-0.5 rounded-full animate-pulse text-white font-bold">AO
                                                    VIVO</span>
                                            @endif
                                        </p>
                                        <p class="text-xs text-gray-400 mt-0.5">
                                            {{ ucfirst($class->weekday) }} • {{ $class->formatted_start }}
                                        </p>
                                    </div>
                                </a>
                            @empty
                                <div class="text-sm text-gray-400 py-2">Nenhuma aula cadastrada para seu idioma.</div>
                            @endforelse

                            <div class="mt-4 pt-4 border-t border-white/5 text-xs text-gray-500">
                                Exclusivo para alunos matriculados
                            </div>
                        </div>
                    </div>
                @endauth

            </div>
        </div>

        <div class="hidden md:flex items-center gap-5 shrink-0">
            @auth
                <span class="text-sm text-gray-300 whitespace-nowrap">Olá, {{ Auth::user()->name }} 👋</span>

                <a href="{{ route('filament.admin.pages.dashboard') }}"
                    class="whitespace-nowrap text-sm px-5 py-2.5 rounded-xl bg-[#82cd29] text-[#020916] font-bold shadow-md shadow-[#82cd29]/10 hover:bg-[#76c022] transition duration-300">
                    Área Administrativa
                </a>

                <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="whitespace-nowrap text-sm px-4 py-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition">Sair</button>
                </form>
            @else
                <a href="{{ route('filament.admin.auth.login') }}"
                    class="text-sm px-4 py-2 rounded-xl text-gray-300 hover:text-white hover:bg-white/5 transition">Entrar</a>
                <a href="{{ route('register') }}"
                    class="text-sm px-5 py-2.5 rounded-xl bg-[#82cd29] text-[#020916] font-bold shadow-md shadow-[#82cd29]/10 hover:bg-[#76c022] transition duration-300">Cadastre-se</a>
            @endauth
        </div>

        <div class="md:hidden">
            <button @click="open = !open"
                class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition"
                aria-label="Abrir Menu">
                <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

    </div>

    <template x-teleport="body">
        <div>
            <div x-show="open" x-transition.opacity @click="open = false"
                class="fixed inset-0 bg-black/70 backdrop-blur-sm z-[9999] md:hidden"></div>

            <div x-show="open" x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
                x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
                x-transition:leave-end="translate-x-full"
                class="fixed top-0 right-0 w-[300px] h-full bg-[#0b1528] border-l border-white/5 shadow-2xl z-[10000] md:hidden flex flex-col overflow-y-auto text-white">

                <div class="flex items-center justify-between px-6 py-5 border-b border-white/5">
                    <span class="font-bold text-lg text-white">Navegação</span>
                    <button @click="open = false"
                        class="p-2 rounded-xl text-gray-400 hover:text-white hover:bg-white/5 transition">✕</button>
                </div>

                <div class="flex-1 px-6 py-6 space-y-5 text-sm font-medium">
                    <a href="{{ route('home') }}" class="block text-gray-300 hover:text-white transition">Início</a>
                    <a href="{{ route('home') }}" class="block text-gray-300 hover:text-white transition">Meus
                        Cursos</a>
                    <a href="{{ route('translator.index') }}"
                        class="block text-gray-300 hover:text-white transition">Tradutor</a>
                    <a href="{{ route('texts.index') }}"
                        class="block text-gray-300 hover:text-white transition">Textos</a>
                    <a href="{{ route('dictionary.index') }}"
                        class="block text-gray-300 hover:text-white transition">Meu
                        Dicionário</a>

                    @auth
                        <div class="pt-5 border-t border-white/5 space-y-4">
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-500">Aulas ao Vivo</p>

                            @forelse($liveClasses as $class)
                                <a href="{{ $class->link }}" target="_blank"
                                    class="flex items-center justify-between p-3 rounded-xl transition {{ $class->is_live ? 'bg-red-600/20 border border-red-500/30' : 'hover:bg-white/5' }}">

                                    <div class="flex items-center gap-3">
                                        <img src="{{ $class->language === 'es' ? 'https://flagcdn.com/w20/es.png' : 'https://flagcdn.com/w20/us.png' }}"
                                            class="rounded shadow-sm" alt="Flag">
                                        <div>
                                            <p class="text-sm font-semibold text-white">
                                                {{ $class->description ?? 'Aula ao Vivo' }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">{{ ucfirst($class->weekday) }} •
                                                {{ $class->formatted_start }}</p>
                                        </div>
                                    </div>

                                    @if ($class->is_live)
                                        <span
                                            class="text-[10px] bg-red-600 px-2 py-0.5 rounded-full animate-pulse text-white font-bold">AO
                                            VIVO</span>
                                    @endif
                                </a>
                            @empty
                                <p class="text-xs text-gray-500">Nenhuma aula para o seu idioma.</p>
                            @endforelse
                        </div>
                    @endauth
                </div>

                <div class="px-6 py-6 border-t border-white/5 space-y-4 bg-[#030d1e]">
                    @auth
                        <p class="text-gray-400 text-sm">Logado como: <br><strong
                                class="text-white">{{ Auth::user()->name }}</strong></p>
                        <a href="{{ route('filament.admin.pages.dashboard') }}"
                            class="block w-full text-center py-3 rounded-xl bg-[#82cd29] text-[#020916] font-bold shadow-md">
                            Área Administrativa
                        </a>
                        <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="w-full text-center py-2.5 rounded-xl border border-white/10 text-gray-300 hover:bg-white/5 transition">Sair
                                da conta</button>
                        </form>
                    @else
                        <a href="{{ route('filament.admin.auth.login') }}"
                            class="block text-center py-2.5 border border-white/10 rounded-xl text-gray-300 hover:bg-white/5 transition">Entrar</a>
                        <a href="{{ route('register') }}"
                            class="block text-center py-3 rounded-xl bg-[#82cd29] text-[#020916] font-bold">Cadastre-se</a>
                    @endauth
                </div>

            </div>
        </div>
    </template>
</nav>

<div class="h-8"></div>
