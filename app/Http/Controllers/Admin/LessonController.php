<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\User;
use App\Models\Vehicle;
use App\Http\Requests\StoreLessonRequest;
use App\Http\Requests\UpdateLessonRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function index(): View
    {
        $lessons = Lesson::with(['enrollment.user', 'instructor', 'vehicle'])
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10);

        return view('admin.lessons.index', compact('lessons'));
    }

    public function create(): View
    {
        $enrollments = Enrollment::with(['user', 'course'])->where('status', 'in_progress')->get();
        $instructors = User::where('role', 'instructor')->with('assignedVehicle')->orderBy('surname')->get();
        $vehicles = Vehicle::orderBy('brand')->get();

        return view('admin.lessons.create', compact('enrollments', 'instructors', 'vehicles'));
    }

    public function store(StoreLessonRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $enrollment = Enrollment::findOrFail($data['enrollment_id']);
        $data['booked_by_id'] = $enrollment->user_id;

        Lesson::create($data);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lekcja została zaplanowana pomyślnie.');
    }

    public function show(Lesson $lesson): View
    {
        $lesson->load(['enrollment.user', 'instructor', 'vehicle']);

        return view('admin.lessons.show', compact('lesson'));
    }

    public function edit(Lesson $lesson): View
    {
        $enrollments = Enrollment::with(['user', 'course'])->get();
        $instructors = User::where('role', 'instructor')->with('assignedVehicle')->orderBy('surname')->get();
        $vehicles = Vehicle::orderBy('brand')->get();

        return view('admin.lessons.edit', compact('lesson', 'enrollments', 'instructors', 'vehicles'));
    }

    public function update(UpdateLessonRequest $request, Lesson $lesson): RedirectResponse
    {
        $data = $request->validated();

        $enrollment = Enrollment::findOrFail($data['enrollment_id']);
        $data['booked_by_id'] = $enrollment->user_id;

        $lesson->update($data);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lekcja została zaktualizowana.');
    }

    public function destroy(Lesson $lesson): RedirectResponse
    {
        $lesson->delete();

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lekcja została usunięta.');
    }
}
