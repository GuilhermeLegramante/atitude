@php
    use Carbon\Carbon;
    use App\Models\LiveClass;

    $now = Carbon::now();
    $today = strtolower(str_replace('-feira', '', $now->locale('pt_BR')->dayName));
    $currentTime = $now->format('H:i');

    $liveClasses = LiveClass::where('active', true)
        ->orderByRaw(
            "
            FIELD(weekday, 
            'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado')
        ",
        )
        ->orderBy('time')
        ->get();

    function isLiveNow($class, $today, $currentTime)
    {
        $start = Carbon::parse($class->time);
        $end = Carbon::parse($class->time)->addHour(); // duração 1h
        return $class->weekday === $today && Carbon::now()->between($start, $end);
    }

    $hasLiveNow = $liveClasses->contains(function ($class) use ($today, $currentTime) {
        return isLiveNow($class, $today, $currentTime);
    });
@endphp


<!-- Navbar -->
<nav x-data="{ open: false, scrolled: false, openAulas: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8);
window.addEventListener('keydown', (e) => { if (e.key === 'Escape') open = false });"
    :class="scrolled ? 'shadow-lg bg-[#24253b]/95 backdrop-blur-md' : 'bg-[#2b2c43]'"
    class="fixed top-0 left-0 w-full z-50 text-white transition-all duration-300 ease-in-out">

    <div class="max-w-[1400px] mx-auto flex items-center justify-between px-8 py-3">

        <!-- LOGO -->
        <div class="flex items-center shrink-0">
            <a href="{{ route('home') }}" class="group">
                <img src="{{ asset('img/atitude_logo_contorno.png') }}"
                    class="w-40 transition duration-300 group-hover:scale-105" alt="Logo">
            </a>
        </div>

        <!-- MENU CENTRAL -->
        <div class="hidden md:flex flex-1 justify-center">

            <div class="flex items-center gap-10 text-sm font-medium tracking-wide">

                @php $route = 'request()->routeName()'; @endphp

                @foreach ([['label' => 'Início', 'route' => 'home'], ['label' => 'Meus Cursos', 'route' => 'home'], ['label' => 'Tradutor', 'route' => 'translator.index'], ['label' => 'Textos', 'route' => 'texts.index'], ['label' => 'Meu Dicionário', 'route' => 'dictionary.index']] as $item)
                    <a href="{{ route($item['route']) }}"
                        class="relative whitespace-nowrap transition duration-300
                   {{ $route == $item['route'] ? 'text-[#c0ff01]' : 'hover:text-[#c0ff01]' }}">

                        {{ $item['label'] }}

                        <!-- Linha animada -->
                        <span
                            class="absolute -bottom-2 left-0 h-[2px] bg-[#c0ff01]
                        transition-all duration-300
                        {{ $route == $item['route'] ? 'w-full' : 'w-0 group-hover:w-full' }}">
                        </span>
                    </a>
                @endforeach

                <!-- MEGA MENU AULAS -->
                @auth
                    <div class="relative group">

                        <button
                            class="flex items-center gap-2 whitespace-nowrap
                               hover:text-[#c0ff01] transition duration-300">

                            Aulas

                            @if ($isSaturday)
                                <span class="text-xs bg-red-600 px-2 py-0.5 rounded-full animate-pulse">
                                    AO VIVO
                                </span>
                            @endif

                        </button>

                        <!-- DROPDOWN MODERNO -->
                        <div
                            class="absolute left-1/2 -translate-x-1/2 mt-6
                            w-[420px] p-6
                            bg-[#1f2033]/95 backdrop-blur-xl
                            border border-white/10
                            rounded-2xl shadow-2xl
                            opacity-0 invisible
                            group-hover:opacity-100
                            group-hover:visible
                            transition-all duration-300">

                            <a href="{{ url('/aula-ao-vivo') }}" target="_blank"
                                class="flex items-center gap-4 p-4 rounded-xl
                              hover:bg-white/5 transition">

                                <img src="https://flagcdn.com/w40/es.png" class="rounded-md shadow-md">

                                <div>
                                    <p class="font-semibold">
                                        Aula de Espanhol Ao Vivo
                                    </p>
                                    <p class="text-xs text-white/60">
                                        Sábados • 7h30
                                    </p>
                                </div>

                            </a>

                            <div class="mt-4 pt-4 border-t border-white/10 text-xs text-white/50">
                                Exclusivo para alunos matriculados
                            </div>

                        </div>

                    </div>
                @endauth

            </div>

        </div>

        <!-- ÁREA DIREITA -->
        <div class="hidden md:flex items-center gap-5 shrink-0">

            @auth
                <span class="text-sm text-white/80 whitespace-nowrap">
                    Olá, {{ Auth::user()->name }} 👋
                </span>

                <a href="{{ route('filament.admin.pages.dashboard') }}"
                    class="whitespace-nowrap text-sm px-5 py-2.5 rounded-xl
                      bg-[#c0ff01] text-[#111827] font-semibold
                      hover:scale-105 hover:shadow-lg
                      transition duration-300">
                    Área Administrativa
                </a>

                <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="whitespace-nowrap text-sm px-4 py-2
                               rounded-xl hover:bg-white/5 transition">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('filament.admin.auth.login') }}"
                    class="text-sm px-4 py-2 rounded-xl hover:bg-white/5 transition">
                    Entrar
                </a>

                <a href="{{ route('register') }}"
                    class="text-sm px-5 py-2.5 rounded-xl
                      bg-[#c0ff01] text-[#111827] font-semibold
                      hover:scale-105 hover:shadow-lg
                      transition duration-300">
                    Cadastre-se
                </a>
            @endauth

        </div>

        <!-- MOBILE BUTTON -->
        <div class="md:hidden">
            <button @click="open = !open" class="p-2 rounded-md hover:bg-white/5">
                <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

    </div>
</nav>

<div class="h-20"></div>
<br><br>
