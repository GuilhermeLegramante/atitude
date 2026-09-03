<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;

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
            $tr = new GoogleTranslate();
            $tr->setSource($request->source === 'auto' ? null : $request->source);
            $tr->setTarget($request->target);

            $translatedText = $tr->translate($request->text);

            return view('translator', [
                'translatedText' => $translatedText,
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
            $tr = new GoogleTranslate();

            // Trata detecção automática caso venha 'auto' no source
            $source = strtolower($request->source) === 'auto' ? null : $request->source;

            $tr->setSource($source);
            $tr->setTarget($request->target);

            $translatedText = $tr->translate($request->text);

            return response()->json([
                'translatedText' => $translatedText,
            ]);
        } catch (\Throwable $e) {
            // Captura Throwable para evitar fatal errors/TypeError do PHP
            return response()->json([
                'error' => 'Erro ao traduzir: ' . $e->getMessage()
            ], 500);
        }
    }
}
