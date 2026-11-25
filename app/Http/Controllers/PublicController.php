<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class PublicController extends Controller
{
    public function home(): View
    {
        $instructorsCount = User::where('role', 'instructor')->count();
        $vehiclesCount = Vehicle::where('status', 'active')->count();
        $completedLessonsCount = Lesson::where('status', 'completed')->count();
        $graduatesCount = Enrollment::where('status', 'completed')->count();

        $upcomingCourses = Course::where(function ($query) {
                $query->whereNull('start_date')
                      ->orWhere('start_date', '>=', Carbon::today());
            })
            ->orderBy('start_date', 'asc')
            ->take(3)
            ->get();

        $studentState = $this->getStudentEnrollmentState();

        return view('public.home', array_merge(compact(
            'instructorsCount',
            'vehiclesCount',
            'completedLessonsCount',
            'graduatesCount',
            'upcomingCourses'
        ), $studentState));
    }

    public function courses(Request $request): View
    {
        $category = in_array($request->query('category'), ['A', 'B', 'C'], true)
            ? $request->query('category')
            : null;

        $query = Course::where(function ($q) {
            $q->whereNull('start_date')
              ->orWhere('start_date', '>=', Carbon::today());
        });

        if ($category) {
            $query->where('category', $category);
        }

        $courses = $query->orderBy('start_date', 'asc')->get();
        $studentState = $this->getStudentEnrollmentState();

        return view('public.courses', array_merge(compact('courses', 'category'), $studentState));
    }

    public function fleet(Request $request): View
    {
        $category = in_array($request->query('category'), ['A', 'B', 'C'], true)
            ? $request->query('category')
            : null;

        $query = Vehicle::where('status', 'active');

        if ($category) {
            $query->where('category', $category);
        }

        $vehicles = $query->orderBy('category', 'asc')
            ->orderBy('brand', 'asc')
            ->get();

        return view('public.fleet', compact('vehicles', 'category'));
    }

    public function about(): View
    {
        $maxBase = config('gamification.max_base_score', 100);
        $penalties = config('gamification.penalties', [
            'minor_mistake' => 2,
            'critical_mistake' => 10,
            'intervention' => 20,
        ]);

        return view('public.about', compact('maxBase', 'penalties'));
    }

    public function contact(): View
    {
        return view('public.contact');
    }

    private function getStudentEnrollmentState(): array
    {
        if (!Auth::check() || Auth::user()->role !== 'student') {
            return [
                'studentEnrollmentsByCourse' => [],
                'blockedCategories' => [],
            ];
        }

        $enrollments = Enrollment::with('course')
            ->where('user_id', Auth::id())
            ->get();

        $studentEnrollmentsByCourse = [];
        $blockedCategories = [];

        foreach ($enrollments as $enrollment) {
            $studentEnrollmentsByCourse[$enrollment->course_id] = $enrollment->status;

            if ($enrollment->course && in_array($enrollment->status, ['waiting', 'in_progress', 'completed'], true)) {
                $blockedCategories[] = $enrollment->course->category;
            }
        }

        return [
            'studentEnrollmentsByCourse' => $studentEnrollmentsByCourse,
            'blockedCategories' => array_unique($blockedCategories),
        ];
    }
}
