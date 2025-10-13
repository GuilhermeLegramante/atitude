<div class="w-full flex flex-col md:flex-row gap-4 p-2">
    <div class="flex-shrink-0 w-full md:w-1/3 bg-gray-100 rounded-xl overflow-hidden shadow-md relative">
        <img src="{{ $getRecord()?->image_path ? asset('storage/' . $getRecord()?->image_path) : asset('img/atitude_logo_contorno.png') }}"
            alt="{{ $getRecord()?->title }}"
            class="w-full h-48 object-cover transition-transform duration-300 hover:scale-105">

        @if ($getRecord()?->video_link)
            <a href="{{ $getRecord()?->video_link }}" target="_blank"
                class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-25 opacity-0 hover:opacity-100 transition duration-300">
                <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M8 5v14l11-7z" />
                </svg>
            </a>
        @endif
    </div>

    <div class="flex-1 flex flex-col justify-between">
        <h3 class="text-lg font-bold text-gray-800 truncate">{{ $getRecord()?->title }}</h3>
        <p class="text-sm text-gray-500 mt-1">{{ $getRecord()?->class->course->name ?? '-' }}</p>
        <p class="text-sm text-gray-500">{{ $getRecord()?->class->name ?? '-' }}</p>
        <p class="text-xs text-gray-400 mt-2">Enviada em: {{ $getRecord()?->created_at->format('d/m/Y') }}</p>

        @if ($getRecord()?->video_link)
            <a href="{{ $getRecord()?->video_link }}" target="_blank"
                class="mt-3 inline-block px-4 py-2 bg-green-600 text-white text-sm font-semibold rounded-lg hover:bg-green-700 transition">
                Assistir Aula
            </a>
        @endif
    </div>
</div>
