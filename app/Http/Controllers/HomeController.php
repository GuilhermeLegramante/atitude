<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        // Carrega todos os cursos com classes e lições
        $courses = Course::with(['classes.lessons'])->latest()->get();

        // Variáveis padrão (para visitante)
        $student = null;
        $lastLesson = null;
        $currentCourse = null;
        $userPoints = 0;
        $position = null;

        // Se o usuário estiver logado
        if (Auth::check()) {
            $student = Auth::user()->student;

            if ($student) {
                $lastLesson = $student->lastWatchedLesson();
                $currentCourse = $lastLesson?->class?->course;
            }

            // Busca XP e posição no ranking
            $xp = DB::table('experiences')
                ->where('user_id', Auth::id())
                ->first();

            $userPoints = $xp->experience_points ?? 0;

            $position = DB::table('experiences')
                ->where('experience_points', '>', $userPoints)
                ->count() + 1;
        }

        return view('home', compact(
            'courses',
            'lastLesson',
            'currentCourse',
            'userPoints',
            'position'
        ));
    }
}
