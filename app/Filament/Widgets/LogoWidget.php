<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogoWidget extends Widget
{
    protected static ?int $sort = 3;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'filament.widgets.logo-widget';

}
