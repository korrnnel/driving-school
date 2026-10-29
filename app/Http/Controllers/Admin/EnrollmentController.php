<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use App\Http\Requests\StoreEnrollmentRequest;
use App\Http\Requests\UpdateEnrollmentRequest;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::with(['user', 'course'])->latest()->paginate(10);
        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function create()
    {
        $students = User::where('role', 'student')->orderBy('surname')->get();
        $courses = Course::latest()->get();

        return view('admin.enrollments.create', compact('students', 'courses'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        Enrollment::create($request->validated());

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Zapis na kurs został pomyślnie dodany.');
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['user', 'course']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function edit(Enrollment $enrollment)
    {
        $students = User::where('role', 'student')->orderBy('surname')->get();
        $courses = Course::latest()->get();

        return view('admin.enrollments.edit', compact('enrollment', 'students', 'courses'));
    }

    public function update(UpdateEnrollmentRequest $request, Enrollment $enrollment)
    {
        $enrollment->update($request->validated());

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Zapis na kurs został pomyślnie zaktualizowany.');
    }

    public function destroy(Enrollment $enrollment)
    {
        $enrollment->delete();

        return redirect()->route('admin.enrollments.index')
            ->with('success', 'Zapis na kurs został usunięty.');
    }
}
