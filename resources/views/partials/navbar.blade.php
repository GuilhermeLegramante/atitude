@php
    use Carbon\Carbon;
    use App\Models\LiveClass;

    $now = Carbon::now();
    $today = strtolower(str_replace('-feira', '', $now->locale('pt_BR')->dayName));

    $student = auth()->user()?->student;

    $liveClasses = LiveClass::where('active', true)
        ->when($student && $student->language !== 'both', function ($query) use ($student) {
            $query->where('language', $student->language);
        })
        ->ordered()
        ->get();

    $hasLiveNow = $liveClasses->contains(function ($class) use ($now, $today) {
        $start = Carbon::parse($class->time);
        $end = Carbon::parse($class->time)->addHour();

        return $class->weekday === $today && $now->between($start, $end);
    });
@endphp


<!-- Navbar -->
<nav x-data="{
    open: false,
    scrolled: false
}" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8);
$watch('open', value => {
    document.body.classList.toggle('overflow-hidden', value);
});"
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


                @foreach ([['label' => 'Início', 'route' => 'home'], ['label' => 'Meus Cursos', 'route' => 'home'], ['label' => 'Tradutor', 'route' => 'translator.index'], ['label' => 'Textos', 'route' => 'texts.index'], ['label' => 'Meu Dicionário', 'route' => 'dictionary.index']] as $item)
                    <a href="{{ route($item['route']) }}"
                        class="relative whitespace-nowrap transition duration-300
{{ request()->routeIs($item['route']) ? 'text-[#c0ff01]' : 'hover:text-[#c0ff01]' }}">

                        {{ $item['label'] }}

                        <!-- Linha animada -->
                        <span
                            class="absolute -bottom-2 left-0 h-[2px] bg-[#c0ff01]
                        transition-all duration-300
                        {{ request()->routeIs($item['route']) ? 'w-full' : 'w-0 group-hover:w-full' }}">
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

                            @if ($hasLiveNow)
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

                            @forelse($liveClasses as $class)
                                @php
                                    $start = \Carbon\Carbon::parse($class->time);
                                    $end = \Carbon\Carbon::parse($class->time)->addHour();
                                    $isLive = $class->weekday === $today && $now->between($start, $end);
                                @endphp

                                <a href="{{ $class->link }}" target="_blank"
                                    class="flex items-center gap-4 p-4 rounded-xl
              transition
              {{ $isLive ? 'bg-red-600/20 border border-red-500/40' : 'hover:bg-white/5' }}">

                                    <!-- BANDEIRA DINÂMICA -->
                                    <img src="{{ $class->language === 'es' ? 'https://flagcdn.com/w40/es.png' : 'https://flagcdn.com/w40/us.png' }}"
                                        class="rounded-md shadow-md">

                                    <div class="flex-1">

                                        <p class="font-semibold flex items-center gap-2">

                                            {{ $class->description ?? 'Aula ao Vivo' }}

                                            @if ($isLive)
                                                <span class="text-xs bg-red-600 px-2 py-0.5 rounded-full animate-pulse">
                                                    AO VIVO
                                                </span>
                                            @endif

                                        </p>

                                        <p class="text-xs text-white/60">
                                            {{ ucfirst($class->weekday) }} •
                                            {{ $start->format('H\hi') }}
                                        </p>

                                    </div>

                                </a>

                            @empty
                                <div class="text-sm text-white/50">
                                    Nenhuma aula cadastrada.
                                </div>
                            @endforelse

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

    <!-- OVERLAY -->
    <div x-show="open" x-transition.opacity @click="open = false"
        class="fixed inset-0 bg-black/60 backdrop-blur-sm z-40 md:hidden">
    </div>

    <!-- SIDEBAR MOBILE -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="translate-x-0"
        x-transition:leave-end="translate-x-full"
        class="fixed top-0 right-0 w-[300px] h-full
            bg-[#1f2033] shadow-2xl z-50 md:hidden
            flex flex-col">

        <!-- HEADER -->
        <div class="flex items-center justify-between px-6 py-5 border-b border-white/10">
            <span class="font-semibold text-lg">Menu</span>

            <button @click="open = false" class="p-2 rounded-md hover:bg-white/5">
                ✕
            </button>
        </div>

        <!-- LINKS -->
        <div class="flex-1 px-6 py-6 space-y-6 text-sm font-medium">

            <a href="{{ route('home') }}" class="block hover:text-[#c0ff01] transition">
                Início
            </a>

            <a href="{{ route('home') }}" class="block hover:text-[#c0ff01] transition">
                Meus Cursos
            </a>

            <a href="{{ route('translator.index') }}" class="block hover:text-[#c0ff01] transition">
                Tradutor
            </a>

            <a href="{{ route('texts.index') }}" class="block hover:text-[#c0ff01] transition">
                Textos
            </a>

            <a href="{{ route('dictionary.index') }}" class="block hover:text-[#c0ff01] transition">
                Meu Dicionário
            </a>

            @auth
                <div class="pt-4 border-t border-white/10 space-y-4 max-h-64 overflow-y-auto pr-2">

                    <p class="text-sm text-white/60">
                        Aulas ao Vivo
                    </p>

                    @forelse($liveClasses as $class)
                        @php
                            $start = \Carbon\Carbon::parse($class->time);
                            $end = \Carbon\Carbon::parse($class->time)->addHour();
                            $isLive = $class->weekday === $today && $now->between($start, $end);
                        @endphp

                        <a href="{{ $class->link }}" target="_blank"
                            class="flex items-center justify-between p-3 rounded-xl transition
               {{ $isLive ? 'bg-red-600/20 border border-red-500/40' : 'hover:bg-white/5' }}">

                            <div class="flex items-center gap-3">

                                <!-- Bandeira -->
                                <img src="{{ $class->language === 'es' ? 'https://flagcdn.com/w20/es.png' : 'https://flagcdn.com/w20/us.png' }}"
                                    class="rounded shadow-md">

                                <div>
                                    <p class="text-sm font-semibold">
                                        {{ $class->description ?? 'Aula ao Vivo' }}
                                    </p>

                                    <p class="text-xs text-white/60">
                                        {{ ucfirst($class->weekday) }} •
                                        {{ $start->format('H\hi') }}
                                    </p>
                                </div>

                            </div>

                            @if ($isLive)
                                <span class="text-xs bg-red-600 px-2 py-0.5 rounded-full animate-pulse">
                                    AO VIVO
                                </span>
                            @endif

                        </a>

                    @empty
                        <p class="text-xs text-white/50">
                            Nenhuma aula cadastrada.
                        </p>
                    @endforelse

                </div>
            @endauth

        </div>

        <!-- FOOTER AUTH -->
        <div class="px-6 py-6 border-t border-white/10 space-y-4 max-h-64 overflow-y-auto pr-2">

            @auth
                <p class="text-white/60 text-sm">
                    Olá, {{ Auth::user()->name }}
                </p>

                <a href="{{ route('filament.admin.pages.dashboard') }}"
                    class="block w-full text-center py-2 rounded-xl
                      bg-[#c0ff01] text-[#111827] font-semibold">
                    Área Administrativa
                </a>

                <form action="{{ route('filament.admin.auth.logout') }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="w-full text-center py-2 rounded-xl
                               border border-white/10">
                        Sair
                    </button>
                </form>
            @else
                <a href="{{ route('filament.admin.auth.login') }}"
                    class="block text-center py-2 border border-white/10 rounded-xl">
                    Entrar
                </a>

                <a href="{{ route('register') }}"
                    class="block text-center py-2 rounded-xl
                      bg-[#c0ff01] text-[#111827] font-semibold">
                    Cadastre-se
                </a>
            @endauth

        </div>

    </div>
</nav>

<div class="h-20"></div>
<br><br>
