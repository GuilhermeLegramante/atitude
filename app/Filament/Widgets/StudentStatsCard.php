<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class StudentStatsCard extends BaseWidget
{
    protected function getStats(): array
    {
        $student = Auth::user()->student;

        $totalWatched = DB::table('lesson_student')->where('student_id', $student->id)->where('watched', 1)->count();

        $xp = DB::table('experiences')
            ->where('user_id', Auth::id())
            ->get()
            ->first();

        $userPoints = $xp->experience_points ?? 0;

        // Conta quantos usuários têm mais pontos que ele
        $position = DB::table('experiences')
            ->where('experience_points', '>', $userPoints)
            ->count() + 1;

        return [
            Stat::make('Aulas Assistidas', $totalWatched)
                ->description('Total')
                ->color('primary')
                ->icon('heroicon-o-ticket'),

            Stat::make('XP', $userPoints)
                ->description($position . '° Lugar')
                ->color('info')
                ->icon('heroicon-o-star'),
        ];
    }
}
