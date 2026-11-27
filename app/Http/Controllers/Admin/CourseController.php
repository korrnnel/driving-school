<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Http\Requests\StoreCourseRequest;
use App\Http\Requests\UpdateCourseRequest;
use Illuminate\Http\Request;

class CourseController extends Controller
{
  public function index(Request $request)
    {
        $category = in_array($request->query('category'), ['A', 'B', 'C'], true)
            ? $request->query('category')
            : null;
        $type = in_array($request->query('type'), ['standard', 'accelerated', 'weekend'], true)
            ? $request->query('type')
            : null;

        $query = Course::query();

        if ($category) {
            $query->where('category', $category);
        }

        if ($type) {
            $query->where('type', $type);
        }

        $courses = $query->latest()->paginate(10)->withQueryString();

        return view('admin.courses.index', compact('courses', 'category', 'type'));
    }
    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(StoreCourseRequest $request)
    {
        Course::create($request->validated());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Kurs został pomyślnie utworzony.');
    }

    public function show(Course $course)
    {
        return view('admin.courses.show', compact('course'));
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(UpdateCourseRequest $request, Course $course)
    {
        $course->update($request->validated());

        return redirect()->route('admin.courses.index')
            ->with('success', 'Kurs został pomyślnie zaktualizowany.');
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return redirect()->route('admin.courses.index')
            ->with('success', 'Kurs został pomyślnie usunięty.');
    }
}
