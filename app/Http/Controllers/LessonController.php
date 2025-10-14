<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson; // ajuste conforme o nome do seu modelo

class LessonController extends Controller
{
    // public function show(Lesson $lesson)
    // {
    //     // Carrega relacionamentos se houver
    //     $lesson->load(['teacher', 'module.lessons']);

    //     // Exemplo de dados extras (caso não tenha ainda)
    //     $lesson->recommended = Lesson::where('id', '!=', $lesson->id)->take(4)->get();
    //     $lesson->progressPercent = 35;
    //     $lesson->courseProgress = 60;
    //     $lesson->studentLevel = 'Intermediário';
    //     $lesson->studentXp = 320;

    //     return view('lessons.show', compact('lesson'));
    // }

    public function show()
    {
        $lesson = (object) [
            'title' => 'Aula 1 — Introdução ao Inglês',
            'subtitle' => 'Aprenda as bases do idioma',
            'description' => 'Nesta aula você vai aprender as expressões básicas e a pronúncia correta.',
            'video_url' => 'https://www.youtube.com/watch?v=FJtwIGMiXX8',
            'duration' => '12min',
            'teacher' => (object) ['name' => 'Prof. John Doe', 'avatar' => 'https://i.pravatar.cc/100?img=3'],
            'progressPercent' => 40,
            'courseProgress' => 60,
            'studentLevel' => 'Iniciante',
            'studentXp' => 120,
            'resources' => [
                ['name' => 'Apostila PDF', 'url' => '#'],
                ['name' => 'Lista de exercícios', 'url' => '#'],
            ],
            'module' => (object) [
                'title' => 'Módulo 1 — Fundamentos',
                'lessons_count' => 8,
                'lessons' => [
                    (object) ['title' => 'Aula 1', 'url' => '#', 'duration' => '10min'],
                    (object) ['title' => 'Aula 2', 'url' => '#', 'duration' => '12min'],
                ],
            ],
            'recommended' => [],
        ];

        return view('lessons.show', compact('lesson'));
    }
}
