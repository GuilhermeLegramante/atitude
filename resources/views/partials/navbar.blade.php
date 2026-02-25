@php
    use Carbon\Carbon;
    use App\Models\LiveClass;

    $now = Carbon::now();
    $today = strtolower(str_replace('-feira', '', $now->locale('pt_BR')->dayName));
    $currentTime = $now->format('H:i');

    $liveClasses = LiveClass::where('active', true)
        ->orderByRaw("
            FIELD(weekday, 
            'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado')
        ")
        ->orderBy('time')
        ->get();

    function isLiveNow($class, $today, $currentTime) {
        $start = Carbon::parse($class->time);
        $end = Carbon::parse($class->time)->addHour(); // duração 1h
        return $class->weekday === $today
            && Carbon::now()->between($start, $end);
    }

    $hasLiveNow = $liveClasses->contains(function ($class) use ($today, $currentTime) {
        return isLiveNow($class, $today, $currentTime);
    });
@endphp


<!-- Navbar -->
<nav x-data="{ open: false, scrolled: false, openAulas: false }"
     x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8);
     window.addEventListener('keydown', (e) => { if (e.key === 'Escape') open = false });"
     :class="scrolled ? 'shadow-lg bg-[#24253b]/95 backdrop-blur-md' : 'bg-[#2b2c43]'"
     class="fixed top-0 left-0 w-full z-50 text-white transition-all duration-300 ease-in-out">

    <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">

        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <a href="{{ route('home') }}" class="flex items-center">
                <img src="{{ asset('img/atitude_logo_contorno.png') }}"
                     class="w-36 md:w-40 transition-all duration-300" />
            </a>
        </div>

        <!-- Desktop -->
        <div class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">

            <a href="{{ route('home') }}" class="hover:text-[#c0ff01] transition-colors">Início</a>
            <a href="{{ route('home') }}" class="hover:text-[#c0ff01] transition-colors">Meus Cursos</a>
            <a href="{{ route('translator.index') }}" class="hover:text-[#c0ff01] transition-colors">Tradutor</a>
            <a href="{{ route('texts.index') }}" class="hover:text-[#c0ff01] transition-colors">Textos</a>
            <a href="{{ route('dictionary.index') }}" class="hover:text-[#c0ff01] transition-colors">
                Meu Dicionário
            </a>

            @auth
            <div class="relative" @click.away="openAulas = false">

                <button @click="openAulas = !openAulas"
                        class="flex items-center gap-2 hover:text-[#c0ff01] transition">

                    Aulas

                    @if($hasLiveNow)
                        <span class="text-xs bg-red-600 px-2 py-0.5 rounded-full animate-pulse">
                            AO VIVO
                        </span>
                    @endif

                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <!-- Dropdown -->
                <div x-show="openAulas" x-transition
                     class="absolute mt-3 w-72 bg-[#24253b] rounded-lg shadow-xl border border-white/10">

                    @forelse ($liveClasses as $class)

                        @php
                            $isLive = isLiveNow($class, $today, $currentTime);
                        @endphp

                        <a href="{{ $class->link }}" target="_blank"
                           class="flex items-center gap-3 px-4 py-3 transition
                           {{ $isLive ? 'bg-red-600/20 border-l-4 border-red-500' : 'hover:bg-white/5' }}">

                            <div>
                                <p class="font-semibold flex items-center gap-2">

                                    @if($class->language === 'es')
                                        <img src="https://flagcdn.com/w20/es.png" width="20">
                                    @else
                                        <img src="https://flagcdn.com/w20/us.png" width="20">
                                    @endif

                                    {{ $class->description ?? 'Aula ao Vivo' }}

                                    @if($isLive)
                                        <span class="text-xs bg-red-600 px-2 py-0.5 rounded-full animate-pulse">
                                            AO VIVO
                                        </span>
                                    @endif
                                </p>

                                <p class="text-xs text-white/60">
                                    {{ ucfirst($class->weekday) }} • 
                                    {{ \Carbon\Carbon::parse($class->time)->format('H\hi') }}
                                </p>
                            </div>
                        </a>

                    @empty
                        <div class="px-4 py-4 text-sm text-white/50">
                            Nenhuma aula cadastrada.
                        </div>
                    @endforelse

                    <div class="px-4 py-2 text-xs text-white/50 border-t border-white/10">
                        Apenas para alunos
                    </div>
                </div>
            </div>
            @endauth
        </div>

        <!-- Auth Desktop -->
        <div class="hidden md:flex items-center gap-3">
            @auth
                <span class="text-sm text-white/80">
                    Olá, {{ Auth::user()->name }} 👋
                </span>

                <a href="{{ route('filament.admin.pages.dashboard') }}"
                   class="text-sm px-4 py-2 rounded-md bg-[#c0ff01] text-[#111827]
                   font-semibold hover:bg-[#aaff00] transition">
                    Área Administrativa
                </a>

                <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                            class="text-sm px-4 py-2 rounded-md hover:bg-white/5 transition text-white/80">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('filament.admin.auth.login') }}"
                   class="text-sm px-4 py-2 rounded-md hover:bg-white/5 transition">
                    Entrar
                </a>
                <a href="{{ route('register') }}"
                   class="text-sm px-4 py-2 rounded-md bg-[#c0ff01] text-[#111827]
                   font-semibold hover:bg-[#aaff00] transition">
                    Cadastre-se
                </a>
            @endauth
        </div>
    </div>
</nav>

<div class="h-20"></div>
<br><br>