<?php

namespace App\Http\Controllers;

use App\Models\UserDictionary;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DictionaryController extends Controller
{
    public function index()
    {
        $words = UserDictionary::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dictionary.index', compact('words'));
    }

    public function save(Request $request)
    {
        $request->validate([
            'word' => 'required|string',
            'translation' => 'required|string',
        ]);

        $user = $request->user();

        $user->dictionaryEntries()->updateOrCreate(
            ['word' => $request->word],
            ['translation' => $request->translation]
        );

        return response()->json(['success' => true]);
    }
}
