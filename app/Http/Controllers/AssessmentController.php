<?php

namespace App\Http\Controllers;

use App\Models\Assessment;
use App\Models\Answer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        return response()->json(['success' => true]);
    }
}
