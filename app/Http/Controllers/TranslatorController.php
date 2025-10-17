<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Datlechin\GoogleTranslate\Facades\GoogleTranslate;

class TranslatorController extends Controller
{
    public function index()
    {
        return view('translator');
    }

    public function translate(Request $request)
    {
        $request->validate([
            'source' => 'required',
            'target' => 'required',
            'text' => 'required|string|max:5000',
        ]);

        try {
            $translator = GoogleTranslate::source($request->source)
                ->target($request->target)
                ->translate($request->text);

            return view('translator', [
                'translatedText' => $translator->getTranslatedText(),
                'source' => $request->source,
                'target' => $request->target,
                'originalText' => $request->text,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao traduzir: ' . $e->getMessage()]);
        }
    }

    // Endpoint para AJAX (modal)
    public function ajaxTranslate(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:500',
            'source' => 'required|string',
            'target' => 'required|string',
        ]);

        try {
            $translator = GoogleTranslate::source($request->source)
                ->target($request->target)
                ->translate($request->text);

            return response()->json([
                'translatedText' => $translator->getTranslatedText(),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erro ao traduzir: ' . $e->getMessage()
            ], 500);
        }
    }
}
