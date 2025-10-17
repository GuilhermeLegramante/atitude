<?php

namespace App\Http\Controllers;

use App\Models\Text;
use App\Models\UserDictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TextController extends Controller
{
    public function index()
    {
        $texts = \App\Models\Text::orderBy('created_at', 'desc')->paginate(10);
        return view('texts.index', compact('texts'));
    }

    public function show(Text $text)
    {
        return view('texts.show', compact('text'));
    }

    public function saveWord(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
            'translation' => 'required|string',
        ]);

        $user = Auth::user();

        UserDictionary::updateOrCreate(
            ['user_id' => $user->id, 'word' => $request->word],
            ['translation' => $request->translation]
        );

        return response()->json(['success' => true]);
    }
}
