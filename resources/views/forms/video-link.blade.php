<div>
    @if (isset($videoPath))
        <video controls class="w-full max-w-md">
            <source src="{{ asset('/storage/' . $videoPath) }}" type="video/mp4">
            Seu navegador não suporta o elemento de vídeo.
        </video>
    @endif
</div>
