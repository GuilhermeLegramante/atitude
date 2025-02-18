<x-filament-panels::page @class([
    'fi-resource-view-record-page',
    'fi-resource-' . str_replace('/', '-', $this->getResource()::getSlug()),
    'fi-resource-record-' . $record->getKey(),
])>

    {{-- <div wire:key="{{ $this->getId() }}.forms.{{ $this->getFormStatePath() }}">
        {{ $this->form }}
    </div> --}}

    <x-filament-panels::form :wire:key="$this->getId() . '.forms.' . $this->getFormStatePath()" wire:submit="save">
        {{ $this->form }}

        @if (!$this->hasAnswers)
            <x-filament-panels::form.actions :actions="$this->getCachedFormActions()" :full-width="$this->hasFullWidthFormActions()" />
        @endif
    </x-filament-panels::form>



    <x-filament-panels::page.unsaved-data-changes-alert />

</x-filament-panels::page>
