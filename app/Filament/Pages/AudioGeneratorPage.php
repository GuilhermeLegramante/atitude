<?php

namespace App\Filament\Pages;

use App\Filament\Utils\CustomAudioGenerator;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class AudioGeneratorPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-speaker-wave';

    protected static string $view = 'filament.pages.audio-generator-page';

    protected static ?string $title = 'Converter Texto em Áudio';

    protected static ?string $slug = 'converter-texto-em-audio';

    public static function getNavigationGroup(): ?string
    {
        return 'Ferramentas';
    }

    protected function getFormSchema(): array
    {
        return [
            CustomAudioGenerator::make('audio')
                ->label(''),
        ];
    }
}
