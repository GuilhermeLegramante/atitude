<div class="w-full md:w-1/3 p-2">
    <div class="flex flex-col bg-white shadow-lg rounded-xl overflow-hidden hover:shadow-2xl transition duration-300">
        <div class="relative h-80 w-full flex items-center justify-center overflow-hidden">
            <img src="{{ $getRecord()->image_path ? asset('storage/' . $getRecord()->image_path) : asset('img/atitude_logo_contorno.png') }}"
                alt="{{ $getRecord()->title }}"
                class="max-h-full max-w-full object-contain transition-transform duration-300 hover:scale-105">

            @if ($getRecord()->video_link)
                <a href="{{ $getRecord()->video_link }}" target="_blank"
                    class="absolute inset-0 flex items-center justify-center bg-black bg-opacity-25 opacity-0 hover:opacity-100 transition duration-300">
                    <svg class="w-12 h-12 text-white" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M8 5v14l11-7z" />
                    </svg>
                </a>
            @endif
        </div>
    </div>
</div>
