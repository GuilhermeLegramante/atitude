<?php

namespace App\Filament\Pages;

use Filament\Pages\Page;
use Filament\Forms;
use App\Models\Settings;
use Filament\Notifications\Notification;

class LiveClassSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static string $view = 'pages.live-class-settings';

    protected static ?string $navigationIcon = 'heroicon-o-video-camera';
    protected static ?string $navigationLabel = 'Aulas ao Vivo';
    protected static ?string $navigationGroup = 'Configurações';
    protected static ?string $title = 'Configurações das Aulas ao Vivo';

    public ?string $zoom_link = null;

    public function mount(): void
    {
        $this->form->fill([
            'zoom_link' => Settings::get('zoom_link'),
        ]);
    }

    protected function getFormSchema(): array
    {
        return [
            Forms\Components\TextInput::make('zoom_link')
                ->label('Link da Aula ao Vivo (Zoom)')
                ->url()
                ->required()
                ->helperText('Ex: https://zoom.us/j/asdasd'),
        ];
    }

    protected function getFormStatePath(): string
    {
        return '';
    }

    protected function getActions(): array
    {
        return [
            \Filament\Actions\Action::make('save')
                ->label('Salvar')
                ->action(function () {
                    Settings::set('zoom_link', $this->zoom_link);

                    Notification::make()
                        ->title('Configurações salvas com sucesso')
                        ->success()
                        ->send();
                }),
        ];
    }
}
