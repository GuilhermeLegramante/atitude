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
        $lesson = $lesson->load([
            'materials',
            'class.course',
            'class.lessons',
            'assessments.questions.alternatives',
        ]);

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
