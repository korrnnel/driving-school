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
use Illuminate\Http\Request;

class LessonController extends Controller
{
  public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $instructorId = $request->filled('instructor_id') ? (int) $request->query('instructor_id') : null;
        $status = in_array($request->query('status'), ['scheduled', 'completed', 'no_show', 'cancelled'], true)
            ? $request->query('status')
            : null;
        $date = $request->filled('date') ? (string) $request->query('date') : null;

        $query = Lesson::with(['enrollment.user', 'instructor', 'vehicle']);

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->whereHas('enrollment.user', function ($uq) use ($search) {
                    $uq->where('name', 'like', "%{$search}%")
                       ->orWhere('surname', 'like', "%{$search}%");
                })->orWhereHas('vehicle', function ($vq) use ($search) {
                    $vq->where('registration_number', 'like', "%{$search}%")
                       ->orWhere('brand', 'like', "%{$search}%")
                       ->orWhere('model', 'like', "%{$search}%");
                });
            });
        }

        if ($instructorId) {
            $query->where('instructor_id', $instructorId);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($date) {
            $query->where('date', $date);
        }

        $lessons = $query->orderBy('date', 'desc')
            ->orderBy('start_time', 'desc')
            ->paginate(10)
            ->withQueryString();

        $instructors = User::where('role', 'instructor')->orderBy('surname')->get();

        return view('admin.lessons.index', compact('lessons', 'instructors', 'search', 'instructorId', 'status', 'date'));
    }
    public function create(): View
    {
        $enrollments = Enrollment::where(function($q) {
                $q->whereHas('user', function($query) {
                    $query->whereNull('deleted_at');
                })->whereHas('course', function($query) {
                    $query->whereNull('deleted_at');
                });
            })
            ->with(['user', 'course'])
            ->where('status', 'in_progress')
            ->get();

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
        $enrollments = Enrollment::where(function($q) {
                $q->whereHas('user', function($query) {
                    $query->whereNull('deleted_at');
                })->whereHas('course', function($query) {
                    $query->whereNull('deleted_at');
                });
            })
            ->orWhere('id', $lesson->enrollment_id)
            ->with(['user', 'course'])
            ->get();

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
