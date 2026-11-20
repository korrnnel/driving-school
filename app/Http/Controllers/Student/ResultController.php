<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Services\CohortAnalysisService;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ResultController extends Controller
{
    public function index(CohortAnalysisService $analysisService): View
    {
        $enrollments = Enrollment::where('user_id', Auth::id())
            ->with([
                'course',
                'lessons' => fn ($q) => $q->with(['instructor', 'vehicle'])
                    ->where('status', 'completed')
                    ->orderBy('date', 'desc')
                    ->orderBy('start_time', 'desc'),
                'exams' => fn ($q) => $q->with('instructor')->orderBy('date', 'desc'),
            ])
            ->latest('enrolled_at')
            ->get();

        $courseReports = $enrollments->map(function ($enrollment) use ($analysisService) {
            $completedLessons = $enrollment->lessons;

            $latestLesson = $completedLessons->first();
            $latestAnalysis = $latestLesson ? $analysisService->analyzeLesson($latestLesson) : null;

            $lessonsWithScores = $completedLessons->map(function ($lesson) use ($analysisService) {
                return [
                    'lesson' => $lesson,
                    'score' => $analysisService->calculateScore($lesson),
                ];
            });

            $graduationAnalysis = $enrollment->status === 'completed'
                ? $analysisService->analyzeCompletedEnrollment($enrollment)
                : null;

            return [
                'enrollment' => $enrollment,
                'latest_lesson' => $latestLesson,
                'latest_analysis' => $latestAnalysis,
                'lessons_with_scores' => $lessonsWithScores,
                'graduation_analysis' => $graduationAnalysis,
                'exams' => $enrollment->exams,
            ];
        });

        return view('student.results.index', compact('courseReports'));
    }
}
