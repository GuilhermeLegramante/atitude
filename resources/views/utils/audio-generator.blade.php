<x-dynamic-component :component="$getFieldWrapperView('generator')" :field="$field" :label-sr-only="$isLabelHidden()">

    <div class="grid grid-cols-1 gap-6" x-data="{
        init() {
            let setGeneratedAudioListener = addEventListener('set-generated-audio', (event) => {
                if (event.detail.statePath === '{{ $getStatePath() }}') {
                    $wire.set('{{ $getStatePath() }}', {
                        [event.detail.uuid]: event.detail.localFileName
                    });
                }
            });
        },
    }">
        {{-- <div class="">
            @include('filament-forms::components.file-upload')
        </div> --}}

    </div>
</x-dynamic-component>
