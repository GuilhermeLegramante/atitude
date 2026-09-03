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
            'source' => 'required|string',
            'target' => 'required|string',
            'text' => 'required|string|max:5000',
        ]);

        try {
            // Option 1: Use auto-detection if source is 'auto' or standard codes
            $translatedText = GoogleTranslate::source($request->source)
                ->target($request->target)
                ->translate($request->text);

            // If the package method returns a string directly:
            $resultText = is_object($translatedText) && method_exists($translatedText, 'getTranslatedText')
                ? $translatedText->getTranslatedText()
                : $translatedText;

            return view('translator', [
                'translatedText' => $resultText,
                'source' => $request->source,
                'target' => $request->target,
                'originalText' => $request->text,
            ]);
        } catch (\Throwable $e) {
            return back()->withErrors(['error' => 'Erro ao traduzir: Servidor indisponível ou parâmetros inválidos.']);
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
