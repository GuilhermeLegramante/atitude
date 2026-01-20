<?php

namespace App\Http\Controllers;

use App\Mail\AnswerSent;
use App\Models\Assessment;
use App\Models\Answer;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class AssessmentController extends Controller
{
    public function modal(Assessment $assessment)
    {
        $assessment->load(['questions.alternatives', 'questions.questionType']);

        return view('partials.assessment-content', compact('assessment'));
    }

    public function submit(Request $request, Assessment $assessment)
    {
        $user = Auth::user();

        foreach ($request->input('answers', []) as $questionId => $value) {
            $question = $assessment->questions->find($questionId);
            if (!$question) continue;

            $data = [
                'question_id' => $questionId,
                'user_id' => $user->id,
            ];

            switch ($question->questionType->type_name) {
                case 'Objetiva':
                    $data['alternative_id'] = $value;
                    break;
                case 'Discursiva':
                    $data['answer_text'] = $value;
                    break;
                default:
                    continue 2;
            }

            Answer::updateOrCreate(
                ['question_id' => $questionId, 'user_id' => $user->id],
                $data
            );
        }

        $emails = array_filter([
            'guilhermelegramante@gmail.com',
            'carolinatlorenzoni@gmail.com',
        ]);

        $student = Student::find($user->student->id ?? $user->student->name);
        $teacher = $assessment->lesson->class->course->user->name ?? '';
        $course = $assessment->lesson->class->course->name ?? '';
        $class = $assessment->lesson->class->name ?? '';
        $activity = $assessment->lesson->title ?? '';

        Mail::to($emails)->send(new AnswerSent(
            $teacher,
            $student->name,
            $course,
            $class,
            $activity
        ));

        return response()->json(['success' => true]);
    }

    public function userAnswers(Assessment $assessment)
    {
        $userId = auth()->id();

        $questions = $assessment->questions()
            ->with([
                'answers' => function ($q) use ($userId) {
                    $q->where('user_id', $userId)->with('alternative');
                },
                'alternatives'
            ])
            ->get();

        // Calcula pontuação
        $totalQuestions = $questions->count();
        $correctAnswers = 0;

        foreach ($questions as $question) {
            $answer = $question->answers->first();
            if ($answer && $answer->is_correct) {
                $correctAnswers++;
            }
        }

        $scorePercent = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

        return view('partials.user-answers', compact('questions', 'correctAnswers', 'totalQuestions', 'scorePercent'));
    }
}
