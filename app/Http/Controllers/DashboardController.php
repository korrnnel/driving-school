<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Vehicle;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->role === 'admin') {
            $activeStudents = User::where('role', 'student')
                ->whereHas('enrollments', function ($query) {
                    $query->where('status', 'in_progress');
                })->count();

            $todayLessons = Lesson::where('date', Carbon::today())->count();
            $activeVehicles = Vehicle::where('status', 'active')->count();
            $instructorsCount = User::where('role', 'instructor')->count();

            $enrollments = Enrollment::whereHas('user')->with('course')->get();
            $categoryStats = $enrollments->groupBy(function($enrollment) {
                return $enrollment->course ? $enrollment->course->category : 'Nieznana';
            })->map->count();

            $chartCategoriesLabels = $categoryStats->keys()->map(fn($cat) => 'Kat. ' . $cat)->toJson();
            $chartCategoriesData = $categoryStats->values()->toJson();

            $dates = [];
            $lessonsData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dates[] = $date->format('d.m');
                $lessonsData[] = Lesson::where('date', $date->toDateString())->count();
            }

            $chartDatesJson = json_encode($dates);
            $chartLessonsJson = json_encode($lessonsData);

            return view('admin.dashboard', compact(
                'activeStudents', 'todayLessons', 'activeVehicles', 'instructorsCount',
                'chartCategoriesLabels', 'chartCategoriesData',
                'chartDatesJson', 'chartLessonsJson'
            ));
        }

        elseif ($user->role === 'instructor') {
            $todayLessons = Lesson::where('instructor_id', $user->id)->where('date', Carbon::today())->count();

            $monthLessons = Lesson::where('instructor_id', $user->id)
                ->whereMonth('date', Carbon::now()->month)
                ->whereYear('date', Carbon::now()->year)
                ->where('status', 'completed')
                ->count();

            $uniqueStudents = Lesson::where('instructor_id', $user->id)->distinct('booked_by_id')->count('booked_by_id');

            $statusCounts = [
                'Zakończone' => Lesson::where('instructor_id', $user->id)->where('status', 'completed')->count(),
                'Zaplanowane' => Lesson::where('instructor_id', $user->id)->where('status', 'scheduled')->count(),
                'Nieobecności' => Lesson::where('instructor_id', $user->id)->where('status', 'no_show')->count(),
                'Odwołane' => Lesson::where('instructor_id', $user->id)->where('status', 'cancelled')->count(),
            ];
            $instructorStatusesLabels = json_encode(array_keys($statusCounts));
            $instructorStatusesData = json_encode(array_values($statusCounts));

            $dates = [];
            $instructorLessonsData = [];
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $dates[] = $date->format('d.m');
                $instructorLessonsData[] = Lesson::where('instructor_id', $user->id)
                    ->where('date', $date->toDateString())
                    ->count();
            }
            $instructorDatesJson = json_encode($dates);
            $instructorLessonsJson = json_encode($instructorLessonsData);

            return view('instructor.dashboard', compact(
                'todayLessons', 'monthLessons', 'uniqueStudents',
                'instructorStatusesLabels', 'instructorStatusesData',
                'instructorDatesJson', 'instructorLessonsJson'
            ));
        }

        return view('dashboard');
    }
}
