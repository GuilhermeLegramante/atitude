<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson; // ajuste conforme o nome do seu modelo
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LessonController extends Controller
{
    public function toggleWatched(Lesson $lesson)
    {
        $user = auth()->user();

        $current = $user->student->watchedLessons()->where('lesson_id', $lesson->id)->first()?->pivot->watched ?? false;

        $user->student->watchedLessons()->syncWithoutDetaching([
            $lesson->id => ['watched' => !$current]
        ]);

        return back();
    }

    public function show(Lesson $lesson)
    {
        // $lesson = (object) [
        //     'title' => 'Aula 1 — Introdução ao Inglês',
        //     'subtitle' => 'Aprenda as bases do idioma',
        //     'description' => 'Nesta aula você vai aprender as expressões básicas e a pronúncia correta.',
        //     'video_url' => 'https://www.youtube.com/watch?v=FJtwIGMiXX8',
        //     'duration' => '12min',
        //     'teacher' => (object) ['name' => 'Prof. John Doe', 'avatar' => 'https://i.pravatar.cc/100?img=3'],
        //     'progressPercent' => 40,
        //     'courseProgress' => 60,
        //     'studentLevel' => 'Iniciante',
        //     'studentXp' => 120,
        //     'resources' => [
        //         ['name' => 'Apostila PDF', 'url' => '#'],
        //         ['name' => 'Lista de exercícios', 'url' => '#'],
        //     ],
        //     'module' => (object) [
        //         'title' => 'Módulo 1 — Fundamentos',
        //         'lessons_count' => 8,
        //         'lessons' => [
        //             (object) ['title' => 'Aula 1', 'url' => '#', 'duration' => '10min'],
        //             (object) ['title' => 'Aula 2', 'url' => '#', 'duration' => '12min'],
        //         ],
        //     ],
        //     'recommended' => [],
        // ];

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


        return view('lessons.show', compact('lesson', 'lastLesson', 'currentCourse', 'userPoints', 'position'));
    }
}
