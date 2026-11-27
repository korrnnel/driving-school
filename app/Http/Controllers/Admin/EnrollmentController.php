<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\View\View;

class EnrollmentController extends Controller
{
   public function index(Request $request): View
    {
        $status = in_array($request->query('status'), ['waiting', 'in_progress', 'completed', 'dropped', 'cancelled'], true)
            ? $request->query('status')
            : null;
        $search = trim((string) $request->query('search', ''));
        $category = in_array($request->query('category'), ['A', 'B', 'C'], true)
            ? $request->query('category')
            : null;

        $query = Enrollment::with(['user', 'course']);

        if ($status) {
            $query->where('status', $status);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('pkk_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('surname', 'like', "%{$search}%");
                  });
            });
        }

        if ($category) {
            $query->whereHas('course', function ($cq) use ($category) {
                $cq->where('category', $category);
            });
        }

        $enrollments = $query->latest()->paginate(10)->withQueryString();
        $waitingCount = Enrollment::where('status', 'waiting')->count();

        return view('admin.enrollments.index', compact('enrollments', 'status', 'search', 'category', 'waitingCount'));
    }

    public function create(): View
    {
        $students = User::where('role', 'student')->orderBy('surname')->get();

        $courses = Course::whereNull('start_date')
            ->orWhere('start_date', '>=', Carbon::today())
            ->latest()
            ->get();

        return view('admin.enrollments.create', compact('students', 'courses'));
    }

    public function store(StoreEnrollmentRequest $request): RedirectResponse
    {
        Enrollment::create($request->validated());

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Zapis na kurs został pomyślnie dodany.');
    }

    public function show(Enrollment $enrollment): View
    {
        $enrollment->load(['user', 'course']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment): View
    {
        $students = User::where('role', 'student')->orderBy('surname')->get();

        $courses = Course::where(function ($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '>=', Carbon::today());
            })
            ->orWhere('id', $enrollment->course_id)
            ->latest()
            ->get();

        return view('admin.enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment): RedirectResponse
    {
        $enrollment->update($request->validated());

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Zapis na kurs został pomyślnie zaktualizowany.');
    }

    public function approve(Enrollment $enrollment): RedirectResponse
    {
        if ($enrollment->status !== 'waiting') {
            return redirect()->route('admin.enrollments.index')
                ->with('error', 'Można zatwierdzić tylko oczekujące zgłoszenie.');
        }

        $enrollment->update(['status' => 'in_progress']);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Zgłoszenie kursanta zostało zatwierdzone.');
    }

    public function reject(Enrollment $enrollment): RedirectResponse
    {
        if ($enrollment->status !== 'waiting') {
            return redirect()->route('admin.enrollments.index')
                ->with('error', 'Można odrzucić tylko oczekujące zgłoszenie.');
        }

        $enrollment->update(['status' => 'cancelled']);

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Zgłoszenie kursanta zostało odrzucone.');
    }

    public function destroy(Enrollment $enrollment): RedirectResponse
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Zapis na kurs został usunięty.');
    }
}
