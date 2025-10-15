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
        // Carrega os cursos com suas classes e lições
        $courses = Course::with(['classes.lessons'])->latest()->get();

        $student = auth()->user()->student;

        $lastLesson = $student->lastWatchedLesson();

        $currentCourse = $lastLesson?->class?->course;

        $xp = DB::table('experiences')
            ->where('user_id', Auth::id())
            ->get()
            ->first();

        $userPoints = $xp->experience_points ?? 0;

        $position = DB::table('experiences')
            ->where('experience_points', '>', $userPoints)
            ->count() + 1;

        return view('home', compact('courses', 'lastLesson', 'currentCourse', 'userPoints', 'position'));
    }
}
