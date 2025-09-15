{{-- @php
    $user = filament()->auth()->user();
@endphp

<x-filament-widgets::widget class="fi-account-widget relative overflow-hidden">
    <div class="absolute inset-0 flex justify-center items-center pointer-events-none">
        <img src="{{ asset('img/mascote.png') }}" alt="Mascote" class="w-32 opacity-10 object-contain" />
    </div>

    <div class="relative z-10 flex flex-col items-center gap-y-2 p-4">
        <p class="text-lg text-gray-800 dark:text-gray-200 font-semibold text-center">
            Não adie seus SONHOS. Tudo é questão de...
        </p>

        <div class="flex justify-center items-center w-20 h-20">
            <img src="{{ asset('img/logo_atitude_idiomas azul.png') }}" alt="{{ config('app.name') }}"
                class="max-h-20 object-contain">
        </div>
    </div>
</x-filament-widgets::widget> --}}



<x-filament-widgets::widget class="fi-account-widget relative overflow-hidden">
    <img src="{{ asset('img/mascote.png') }}" alt="Mascote"
        class="w-64 opacity-10 object-contain rounded shadow-lg shadow-indigo-500/40" />

    {{-- <div class="relative z-10 flex flex-col items-center gap-y-2 p-4">
        <p class="text-lg text-gray-800 dark:text-gray-200 font-semibold text-center">
            Não adie seus SONHOS. Tudo é questão de...
        </p>

        <div class="flex justify-center items-center w-20 h-20">
            <img src="{{ asset('img/logo_atitude_idiomas azul.png') }}" alt="{{ config('app.name') }}"
                class="max-h-20 object-contain">
        </div>
    </div> --}}
</x-filament-widgets::widget>
