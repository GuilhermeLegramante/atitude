  <!-- Navbar fixa com menu mobile funcional -->
  <nav x-data="{ open: false, scrolled: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 8);
  // fecha o menu com ESC
  window.addEventListener('keydown', (e) => { if (e.key === 'Escape') open = false });"
      :class="scrolled ? 'shadow-lg bg-[#24253b]/95 backdrop-blur-md' : 'bg-[#2b2c43]'"
      class="fixed top-0 left-0 w-full z-50 text-white transition-all duration-300 ease-in-out">
      <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">
          <div class="flex items-center space-x-3">
              <a href="#" class="flex items-center">
                  <img src="{{ asset('img/atitude_logo_contorno.png') }}" alt="Logo"
                      class="w-36 md:w-40 transition-all duration-300" />
              </a>
          </div>

          <!-- Links desktop -->
          <div class="hidden md:flex space-x-8 text-sm font-medium tracking-wide">
              <a href="{{ route('home') }}" class="hover:text-[#c0ff01] transition-colors">Início</a>
              <a href="{{ route('home') }}" class="hover:text-[#c0ff01] transition-colors">Meus Cursos</a>
              {{-- <a href="#" class="hover:text-[#c0ff01] transition-colors">Perfil</a> --}}
          </div>

          <!-- Ações / botão mobile -->
          <div class="flex items-center gap-3">
              <div class="hidden md:flex items-center gap-3">
                  @auth
                      <span class="text-sm text-white/80">Olá, {{ Auth::user()->name }} 👋</span>

                      <a href="{{ route('filament.admin.pages.dashboard') }}"
                          class="text-sm px-4 py-2 rounded-md bg-[#c0ff01] text-[#111827] font-semibold hover:bg-[#aaff00] transition">
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
                  @endauth
              </div>


              <!-- botão mobile -->
              <div class="md:hidden">
                  <button @click="open = !open" :aria-expanded="open.toString()" aria-label="Abrir menu"
                      class="p-2 rounded-md hover:bg-white/5 focus:outline-none focus:ring-2 focus:ring-sky-400">
                      <svg x-show="!open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                          viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                      </svg>

                      <svg x-show="open" xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                          viewBox="0 0 24 24" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </button>
              </div>
          </div>
      </div>

      <!-- Painel mobile (backdrop + menu) -->
      <div x-show="open" x-transition.opacity class="fixed inset-0 z-40" style="display: none;">
          <!-- backdrop -->
          <div class="absolute inset-0 bg-black/40" @click="open = false" aria-hidden="true"></div>

          <!-- painel lateral -->
          <div x-show="open" x-transition:enter="transition transform duration-300"
              x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
              x-transition:leave="transition transform duration-250" x-transition:leave-start="translate-x-0"
              x-transition:leave-end="translate-x-full" @click.away="open = false"
              class="absolute right-0 top-0 h-full w-4/5 max-w-xs bg-[#2b2c43] text-white shadow-xl p-6"
              style="display: none;">
              <div class="flex items-center justify-between mb-6">
                  <div class="flex items-center gap-3">
                      <img src="{{ asset('img/atitude_logo_contorno.png') }}" alt="Logo" class="w-28" />
                  </div>
                  <button @click="open = false" class="p-1 rounded-md hover:bg-white/5 focus:outline-none">
                      <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                          stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                      </svg>
                  </button>
              </div>

              <nav class="flex flex-col space-y-3">
                  <a href="#" class="px-3 py-2 rounded-md hover:bg-white/5 transition">Início</a>
                  <a href="{{ route('home') }}" class="px-3 py-2 rounded-md hover:bg-white/5 transition">Meus Cursos</a>
                  {{-- <a href="#" class="px-3 py-2 rounded-md hover:bg-white/5 transition">Perfil</a> --}}
                  {{-- <a href="#" class="px-3 py-2 rounded-md hover:bg-white/5 transition">Favoritos</a>
                    <a href="#" class="px-3 py-2 rounded-md hover:bg-white/5 transition">Configurações</a> --}}
              </nav>

              <div class="mt-6 border-t border-white/10 pt-4">
                  @auth
                      <div class="text-center text-white">
                          <p class="font-semibold">Olá, {{ Auth::user()->name }} 👋</p>
                          <a href="{{ route('filament.admin.pages.dashboard') }}"
                              class="mt-2 inline-block bg-[#c0ff01] text-[#111827] font-semibold rounded-md px-4 py-2">
                              Acessar painel
                          </a>
                      </div>
                  @else
                      <a href="{{ route('filament.admin.auth.login') }}"
                          class="block w-full text-center bg-[#c0ff01] text-[#111827] font-semibold rounded-md px-4 py-2">
                          Entrar
                      </a>
                  @endauth
              </div>

          </div>
      </div>
  </nav>

  <!-- Espaçamento para o conteúdo não ficar sob a navbar fixa -->
  <div class="h-20"></div>

  <br><br>
