@php
    $user = auth()->user();
    $student = $user->student ?? null;

    if ($student) {
        $filters = request()->query('tableFilters') ?? [];
        $classId = $filters['class_id'] ?? $student->classes()->first()?->id;

        $class = $student->classes()->where('id', $classId)->first();

        $totalLessons = $class?->lessons()->count() ?? 0;
        $watchedLessons =
            $class
                ?->lessons()
                ->whereHas('students', function ($q) use ($student) {
                    $q->where('students.id', $student->id)->where('lesson_student.watched', 1);
                })
                ->count() ?? 0;

        $percent = $totalLessons > 0 ? round(($watchedLessons / $totalLessons) * 100) : 0;
    } else {
        $percent = 0;
        $class = null;
    }
@endphp

@if ($class)
    <div class="mb-4 p-4 bg-gray-100 rounded-lg shadow w-full">
        <h3 class="text-lg font-bold">{{ $class->course->name }} - {{ $class->name }}</h3>
        <div class="w-full bg-gray-200 rounded-full h-4 mt-2">
            <div class="bg-green-500 h-4 rounded-full" style="width: {{ $percent }}%"></div>
        </div>
        <p class="text-sm mt-1">{{ $percent }}% das aulas concluídas</p>
    </div>
@endif
