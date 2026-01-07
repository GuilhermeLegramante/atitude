  @php
      use Carbon\Carbon;
      $isSaturday = Carbon::now()->isSaturday();
  @endphp


  <!-- Navbar fixa com menu mobile funcional -->
  <nav x-data="{ open: false, scrolled: false, openAulas: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8);
  window.addEventListener('keydown', (e) => { if (e.key === 'Escape') open = false });"
      :class="scrolled ? 'shadow-lg bg-[#24253b]/95 backdrop-blur-md' : 'bg-[#2b2c43]'"
      class="fixed top-0 left-0 w-full z-50 text-white transition-all duration-300 ease-in-out">

      <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">

          <!-- Logo -->
          <div class="flex items-center space-x-3">
              <a href="{{ route('home') }}" class="flex items-center">
                  <img src="{{ asset('img/atitude_logo_contorno.png') }}" alt="Logo"
                      class="w-36 md:w-40 transition-all duration-300" />
              </a>
          </div>

          <!-- Links desktop -->
          <div class="hidden md:flex items-center space-x-8 text-sm font-medium tracking-wide">

              <a href="{{ route('home') }}" class="hover:text-[#c0ff01] transition-colors">Início</a>
              <a href="{{ route('home') }}" class="hover:text-[#c0ff01] transition-colors">Meus Cursos</a>
              <a href="{{ route('translator.index') }}" class="hover:text-[#c0ff01] transition-colors">Tradutor</a>
              <a href="{{ route('texts.index') }}" class="hover:text-[#c0ff01] transition-colors">Textos</a>
              <a href="{{ route('dictionary.index') }}" class="hover:text-[#c0ff01] transition-colors">
                  Meu Dicionário
              </a>

              <!-- 🔽 MENU AULAS (somente logado) -->
              @auth
                  <div class="relative" @click.away="openAulas = false">

                      <button @click="openAulas = !openAulas"
                          class="flex items-center gap-2 hover:text-[#c0ff01] transition">

                          Aulas

                          @if ($isSaturday)
                              <span class="text-xs bg-red-600 px-2 py-0.5 rounded-full animate-pulse">
                                  AO VIVO
                              </span>
                          @endif

                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                          </svg>
                      </button>

                      <!-- Dropdown -->
                      <div x-show="openAulas" x-transition
                          class="absolute mt-3 w-56 bg-[#24253b] rounded-lg shadow-xl
                            border border-white/10">

                          <a href="{{ url('/aula-ao-vivo') }}" target="_blank"
                              class="flex items-center gap-3 px-4 py-3 hover:bg-white/5 transition">

                              <svg class="w-5 h-5 text-blue-400" fill="currentColor" viewBox="0 0 24 24">
                                  <path
                                      d="M17 10.5V7a2 2 0 0 0-2-2H5A2 2 0 0 0 3 7v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2v-3.5l4 4v-11l-4 4z" />
                              </svg>

                              <div>
                                  <p class="font-semibold flex items-center gap-2">
                                      <img src="https://flagcdn.com/w20/es.png" srcset="https://flagcdn.com/w40/es.png 2x"
                                          width="20" height="14" alt="Espanha" class="rounded-sm" />

                                      Aula de Espanhol Ao Vivo
                                  </p>

                                  <p class="text-xs text-white/60">Sábados • 7h30</p>
                              </div>
                          </a>

                          <div class="px-4 py-2 text-xs text-white/50 border-t border-white/10">
                              Apenas para alunos
                          </div>
                      </div>
                  </div>
              @endauth
          </div>

          <!-- Ações -->
          <div class="flex items-center gap-3">

              <!-- Desktop auth -->
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

              <!-- Botão mobile -->
              <div class="md:hidden">
                  <button @click="open = !open" class="p-2 rounded-md hover:bg-white/5 focus:ring-2 focus:ring-sky-400">
                      <svg x-show="!open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                      </svg>
                      <svg x-show="open" class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </button>
              </div>
          </div>
      </div>

      <!-- MENU MOBILE -->
      <div x-show="open" x-transition.opacity class="fixed inset-0 z-40" style="display:none;">
          <div class="absolute inset-0 bg-black/40" @click="open = false"></div>

          <div x-show="open" x-transition
              class="absolute right-0 top-0 h-full w-4/5 max-w-xs
                    bg-[#2b2c43] p-6 shadow-xl">

              <nav class="flex flex-col space-y-3">

                  @auth
                      <a href="{{ url('/aula-ao-vivo') }}" target="_blank"
                          class="px-4 py-3 rounded-lg bg-blue-600 hover:bg-blue-700
                          flex items-center justify-between font-semibold">

                          <span class="flex items-center gap-2">
                              <img src="https://flagcdn.com/w20/es.png" srcset="https://flagcdn.com/w40/es.png 2x"
                                  width="20" height="14" alt="Espanha" class="rounded-sm" />
                              Aula de Espanhol Ao Vivo
                          </span>


                          @if ($isSaturday)
                              <span class="text-xs bg-red-600 px-2 py-1 rounded-full animate-pulse">
                                  AO VIVO
                              </span>
                          @endif
                      </a>
                  @endauth

                  <a href="{{ route('home') }}" class="px-3 py-2 hover:bg-white/5 rounded-md">Início</a>
                  <a href="{{ route('translator.index') }}"
                      class="px-3 py-2 hover:bg-white/5 rounded-md">Tradutor</a>
                  <a href="{{ route('texts.index') }}" class="px-3 py-2 hover:bg-white/5 rounded-md">Textos</a>
                  <a href="{{ route('dictionary.index') }}" class="px-3 py-2 hover:bg-white/5 rounded-md">
                      Meu Dicionário
                  </a>
              </nav>
          </div>
      </div>
  </nav>

  <div class="h-20"></div>

  <br><br>
