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
use Illuminate\Http\Request;

class ExamController extends Controller
{
   public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $type = in_array($request->query('type'), ['internal', 'state'], true)
            ? $request->query('type')
            : null;
        $category = in_array($request->query('category'), ['theory', 'practice'], true)
            ? $request->query('category')
            : null;
        $result = in_array($request->query('result'), ['passed', 'failed'], true)
            ? $request->query('result')
            : null;

        $query = Exam::with(['enrollment.user', 'enrollment.course', 'instructor']);

        if ($search !== '') {
            $query->whereHas('enrollment.user', function ($uq) use ($search) {
                $uq->where('name', 'like', "%{$search}%")
                   ->orWhere('surname', 'like', "%{$search}%");
            });
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($result) {
            $query->where('result', $result);
        }

        $exams = $query->orderBy('date', 'desc')->paginate(15)->withQueryString();

        return view('admin.exams.index', compact('exams', 'search', 'type', 'category', 'result'));
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
