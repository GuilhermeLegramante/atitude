<?php

namespace App\Providers\Filament;

use App\Filament\Pages\Auth\Login;
use Filament\Forms\Components\Select;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use App\Filament\Pages\Auth\Register;
use App\Filament\Widgets\AccountWidget;
use App\Filament\Widgets\LogoWidget;
use App\Filament\Widgets\StudentProgressChart;
use App\Filament\Widgets\StudentStatsCard;
use App\Http\Middleware\BlockAlunosFromAdmin;
use Filament\Facades\Filament;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Njxqlus\FilamentProgressbar\FilamentProgressbarPlugin;
use Swis\Filament\Backgrounds\FilamentBackgroundsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        Select::configureUsing(function (Select $select): void {
            $select
                ->preload()
                ->searchable();
        });

        TextColumn::configureUsing(function (TextColumn $textColumn): void {
            $textColumn
                ->sortable();
        });

        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login(Login::class)
            ->profile()
            ->registration(Register::class)
            ->colors([
                'primary' => '#2b2c43', // Azul
                'gray' => '#2b2c43', // Azul
                'success' => '#c0ff01', // Verde
            ])
            ->brandName('Atitude Idiomas')
            ->sidebarCollapsibleOnDesktop()
            ->brandLogo(asset('img/atitude_logo_contorno.png'))
            ->brandLogoHeight(fn() => auth()->check() ? '3rem' : '6rem')
            ->favicon(asset('img/icone.png'))
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->plugins([
                FilamentProgressbarPlugin::make()->color('#c0ff01'),
                FilamentBackgroundsPlugin::make(),
                \BezhanSalleh\FilamentShield\FilamentShieldPlugin::make(),
                \FilipFonal\FilamentLogManager\FilamentLogManager::make(),
            ])
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Relatórios'),
                NavigationGroup::make()
                    ->label('Ensino'),
                NavigationGroup::make()
                    ->label('Gamificação'),
                NavigationGroup::make()
                    ->label('Ferramentas'),
                NavigationGroup::make()
                    ->label('Controle de Acesso'),
                NavigationGroup::make()
                    ->label('Configurações')
                    ->collapsed(),
                NavigationGroup::make()
                    ->label('Administração'),
            ])
            ->widgets([
                AccountWidget::class,
                StudentStatsCard::class,
                LogoWidget::class,
            ])
            ->resources([
                config('filament-logger.activity_resource')
            ])
            ->databaseNotifications()
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
                BlockAlunosFromAdmin::class,
            ]);
    }
}
