<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Carrega os cursos com suas classes e lições
        $courses = Course::with(['classes.lessons'])->latest()->get();

        return view('home', compact('courses'));
    }
}
