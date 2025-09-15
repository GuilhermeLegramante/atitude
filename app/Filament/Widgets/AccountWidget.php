<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AccountWidget extends Widget
{
    protected static ?int $sort = -3;

    protected static bool $isLazy = false;

    /**
     * @var view-string
     */
    protected static string $view = 'filament.widgets.account-widget';

    public function getLastAccess(): string
    {
        $lastAccess = DB::table('activity_log')
            ->where('log_name', 'Access')
            ->where('event', 'Login')
            ->where('causer_id', Auth::id())
            ->max('created_at');

        return $lastAccess ? \Carbon\Carbon::parse($lastAccess)->format('d/m/Y H:i') : '-';
    }
}
