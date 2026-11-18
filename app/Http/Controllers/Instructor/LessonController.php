<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Http\Requests\Instructor\UpdateEvaluationRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(): View
    {
        $lessons = Lesson::with(['enrollment.user', 'vehicle'])
            ->where('instructor_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(15);

        return view('instructor.lessons.index', compact('lessons'));
    }

    public function evaluate(Lesson $lesson): View
    {
        if ($lesson->instructor_id !== Auth::id()) {
            abort(403);
        }

        $lesson->load(['enrollment.user', 'enrollment.course']);

        return view('instructor.lessons.evaluate', compact('lesson'));
    }

    public function updateEvaluation(UpdateEvaluationRequest $request, Lesson $lesson): RedirectResponse
    {
        $lesson->update($request->validated());

        return redirect()->route('instructor.lessons.index')
            ->with('success', 'Karta przebiegu jazdy została pomyślnie zapisana.');
    }
}
