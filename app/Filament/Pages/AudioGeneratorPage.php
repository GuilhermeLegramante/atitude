<?php

namespace App\Filament\Pages;

use App\Filament\Utils\CustomAudioGenerator;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Page;

class AudioGeneratorPage extends Page implements HasForms
{
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.audio-generator-page';

    protected static ?string $title = 'Gerador de Áudio';

    protected static ?string $slug = 'gerador-de-audio';

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
