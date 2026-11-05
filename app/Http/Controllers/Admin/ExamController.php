<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Exam;
use App\Models\Enrollment;
use App\Models\User;
use App\Http\Requests\StoreExamRequest;
use App\Http\Requests\UpdateExamRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExamController extends Controller
{
    public function index(): View
    {
        $exams = Exam::with(['enrollment.user', 'enrollment.course', 'instructor'])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('admin.exams.index', compact('exams'));
    }

    public function create(): View
    {

        $enrollments = Enrollment::whereHas('user', function($query) {
                $query->whereNull('deleted_at');
            })
            ->whereHas('course', function($query) {
                $query->whereNull('deleted_at');
            })
            ->with(['user', 'course'])
            ->get();

        $instructors = User::where('role', 'instructor')->orderBy('surname')->get();

        return view('admin.exams.create', compact('enrollments', 'instructors'));
    }

    public function store(StoreExamRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($data['type'] === 'state' || $data['category'] === 'theory') {
            $data['instructor_id'] = null;
        }

        Exam::create($data);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Egzamin został pomyślnie dodany.');
    }

    public function show(Exam $exam): View
    {
        $exam->load(['enrollment.user', 'enrollment.course', 'instructor']);
        return view('admin.exams.show', compact('exam'));
    }

    public function edit(Exam $exam): View
    {
        $enrollments = Enrollment::where(function($q) {
                $q->whereHas('user', function($query) {
                    $query->whereNull('deleted_at');
                })->whereHas('course', function($query) {
                    $query->whereNull('deleted_at');
                });
            })
            ->orWhere('id', $exam->enrollment_id)
            ->with(['user', 'course'])
            ->get();

        $instructors = User::where('role', 'instructor')->orderBy('surname')->get();

        return view('admin.exams.edit', compact('exam', 'enrollments', 'instructors'));
    }

    public function update(UpdateExamRequest $request, Exam $exam): RedirectResponse
    {
        $data = $request->validated();

        if ($data['type'] === 'state' || $data['category'] === 'theory') {
            $data['instructor_id'] = null;
        }

        $exam->update($data);

        return redirect()->route('admin.exams.index')
            ->with('success', 'Dane egzaminu zostały zaktualizowane.');
    }

    public function destroy(Exam $exam): RedirectResponse
    {
        $exam->delete();

        return redirect()->route('admin.exams.index')
            ->with('success', 'Egzamin został usunięty z systemu.');
    }
}
