<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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

        return view('dashboard');
    }
}
