<?php

namespace App\Filament\Utils;

use Closure;
use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Support\Facades\FilamentView;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Livewire\Component as LivewireComponent;

class CustomAudioGenerator extends FileUpload
{

    public static bool $isComponentRegistered = false;

    protected string $view = 'utils.audio-generator';

    public bool | Closure $audioGenerator = true;

    protected function setUp(): void
    {

        parent::setUp();

        $this->acceptedFileTypes([
            'audio/aac',
            'audio/midi',
            'audio/mpeg',
            'audio/ogg',
            'audio/wav',
            'audio/webm',
            'audio/x-m4a',
        ]);

        $this->columnSpanFull();

        $this->maxFiles(1);

        $this->hintAction(

            Action::make('generateNewAudio')
                ->label(Str::ucfirst(__('filament-audio-generator-field::messages.labels.generate-audio')))
                ->icon('heroicon-m-sparkles')
                ->visible($this->audioGenerator)
                ->action(fn (LivewireComponent $livewire) => $livewire->dispatch('open-modal', id: 'generate-an-audio', statePath: $this->getStatePath(), disk: $this->getDiskName(), generator: null))

        );

        if (!static::$isComponentRegistered) {

            FilamentView::registerRenderHook(
                'panels::content.end',
                fn (): View => view('filament-audio-generator-field::modals.generate-an-audio'),
            );

            static::$isComponentRegistered = true;

        }

    }

    public function audioGenerator(bool | Closure $audioGenerator): static
    {

        $this->audioGenerator = $audioGenerator;

        return $this;

    }

    public function getFieldWrapperView(?string $scope = null): string
    {
        if ($scope === 'generator') {

            return $this->getCustomFieldWrapperView() ??
                $this->getContainer()->getCustomFieldWrapperView() ??
                'filament-forms::field-wrapper';

        }

        return 'filament-audio-generator-field::blank-field-wrapper';

    }

}
