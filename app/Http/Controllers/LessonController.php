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
        $student = auth()->user()->student;

        $pivot = $student->watchedLessons()
            ->where('lesson_id', $lesson->id)
            ->first()?->pivot;

        if (!$pivot) {
            // Primeira vez marcando a lição
            $student->watchedLessons()->attach($lesson->id, [
                'watched'    => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } else {
            // Apenas alterna o status
            $student->watchedLessons()->updateExistingPivot($lesson->id, [
                'watched'    => ! $pivot->watched,
                'updated_at' => now(),
            ]);
        }

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

        // $lastLesson = $student->lastWatchedLesson();

        // $currentCourse = $lastLesson?->class?->course;

        $lastLesson = auth()->user()->student?->lastWatchedLesson();

        $currentCourse = auth()->user()->student?->lastWatchedCourse();

        $xp = DB::table('experiences')
            ->where('user_id', Auth::id())
            // ->get()
            ->first();

        $userPoints = $xp->experience_points ?? 0;

        $position = DB::table('experiences')
            ->where('experience_points', '>', $userPoints)
            ->count() + 1;

        return view('lessons.show', compact('lesson', 'lastLesson', 'currentCourse', 'userPoints', 'position'));
    }
}
