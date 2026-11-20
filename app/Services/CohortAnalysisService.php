<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Lesson;
use Illuminate\Support\Carbon;

class CohortAnalysisService
{
    public function analyzeLesson(Lesson $lesson): array
    {
        $score = $this->calculateScore($lesson);
        $average = $this->getCohortAverage($lesson);

        return [
            'score' => $score,
            'cohort_average' => $average,
            'message' => $this->getFeedbackMessage($score, $average),
        ];
    }

    public function calculateScore(Lesson $lesson): int
    {
        $maxBase = config('gamification.max_base_score', 100);
        $penalties = config('gamification.penalties', [
            'minor_mistake' => 2,
            'critical_mistake' => 10,
            'intervention' => 20,
        ]);

        $planned = $lesson->tasks_planned_count;
        $completed = $lesson->tasks_completed_count;

        $baseScore = $planned > 0 ? ($completed / $planned) * $maxBase : $maxBase;

        $penaltyScore = ($lesson->minor_mistakes_count * $penalties['minor_mistake'])
                      + ($lesson->critical_mistakes_count * $penalties['critical_mistake'])
                      + ($lesson->interventions_count * $penalties['intervention']);

        return (int) max(0, round($baseScore - $penaltyScore));
    }

    public function getCompletedPracticeHours(Enrollment $enrollment): float
    {
        $lessons = $enrollment->relationLoaded('lessons')
            ? $enrollment->lessons->where('status', 'completed')
            : $enrollment->lessons()->where('status', 'completed')->get();

        $totalMinutes = 0;
        foreach ($lessons as $lesson) {
            $start = Carbon::parse($lesson->start_time);
            $end = Carbon::parse($lesson->end_time);
            $totalMinutes += abs($start->diffInMinutes($end));
        }

        return round($totalMinutes / 60, 1);
    }

    public function getCohortAverage(Lesson $lesson): ?int
    {
        $lessonNumber = $this->getLessonNumber($lesson);
        $minNumber = max(1, $lessonNumber - 1);
        $maxNumber = $lessonNumber + 1;

        $enrollment = $lesson->enrollment;
        $course = $enrollment->course;

        $cohortEnrollments = Enrollment::whereHas('course', function ($query) use ($course) {
                $query->where('category', $course->category)
                      ->where('type', $course->type);
            })
            ->where('id', '!=', $enrollment->id)
            ->with(['lessons' => function ($q) {
                $q->where('status', 'completed')->orderBy('date', 'asc')->orderBy('start_time', 'asc');
            }])
            ->get();

        $cohortScores = [];

        foreach ($cohortEnrollments as $enr) {
            foreach ($enr->lessons as $index => $l) {
                $currentNum = $index + 1;

                if ($currentNum >= $minNumber && $currentNum <= $maxNumber) {
                    $cohortScores[] = $this->calculateScore($l);
                }
            }
        }

        if (count($cohortScores) === 0) {
            return null;
        }

        return (int) round(array_sum($cohortScores) / count($cohortScores));
    }

    public function analyzeCompletedEnrollment(Enrollment $enrollment): ?array
    {
        if ($enrollment->status !== 'completed') {
            return null;
        }

        $enrollment->loadMissing(['course', 'lessons', 'exams']);
        $course = $enrollment->course;

        $studentMetrics = $this->extractEnrollmentMetrics($enrollment);

        $cohortEnrollments = Enrollment::where('status', 'completed')
            ->whereHas('course', function ($query) use ($course) {
                $query->where('category', $course->category)
                      ->where('type', $course->type);
            })
            ->with(['lessons', 'exams'])
            ->get();

        $durations = [];
        $extraHoursList = [];
        $bestScores = [];
        $avgScores = [];
        $wordPassedFirstTryCount = 0;
        $wordTotalWithExamsCount = 0;

        foreach ($cohortEnrollments as $cohortEnr) {
            $metrics = $this->extractEnrollmentMetrics($cohortEnr);

            if ($metrics['duration_days'] !== null) {
                $durations[] = $metrics['duration_days'];
            }

            $extraHoursList[] = $metrics['extra_hours'];

            if ($metrics['best_score'] !== null) {
                $bestScores[] = $metrics['best_score'];
            }

            if ($metrics['average_score'] !== null) {
                $avgScores[] = $metrics['average_score'];
            }

            if ($metrics['word_attempts'] > 0 && $metrics['word_passed']) {
                $wordTotalWithExamsCount++;
                if ($metrics['word_attempts'] === 1) {
                    $wordPassedFirstTryCount++;
                }
            }
        }

        $cohortAvgDuration = count($durations) > 0 ? (int) round(array_sum($durations) / count($durations)) : null;
        $cohortAvgExtraHours = count($extraHoursList) > 0 ? round(array_sum($extraHoursList) / count($extraHoursList), 1) : 0;
        $cohortAvgBestScore = count($bestScores) > 0 ? (int) round(array_sum($bestScores) / count($bestScores)) : null;
        $cohortAvgScore = count($avgScores) > 0 ? (int) round(array_sum($avgScores) / count($avgScores)) : null;
        $cohortFirstTryRate = $wordTotalWithExamsCount > 0 ? (int) round(($wordPassedFirstTryCount / $wordTotalWithExamsCount) * 100) : null;

        $durationDiffPercent = null;
        if ($studentMetrics['duration_days'] !== null && $cohortAvgDuration > 0) {
            $durationDiffPercent = (int) round((($cohortAvgDuration - $studentMetrics['duration_days']) / $cohortAvgDuration) * 100);
        }

        $topPercent = null;
        if ($studentMetrics['average_score'] !== null && count($avgScores) > 0) {
            $betterOrEqual = collect($avgScores)->filter(fn ($s) => $s >= $studentMetrics['average_score'])->count();
            $topPercent = (int) max(1, ceil(($betterOrEqual / count($avgScores)) * 100));
        }

        return [
            'duration_days' => $studentMetrics['duration_days'],
            'cohort_avg_duration' => $cohortAvgDuration,
            'duration_diff_percent' => $durationDiffPercent,
            'word_attempts' => $studentMetrics['word_attempts'],
            'word_passed' => $studentMetrics['word_passed'],
            'cohort_first_try_rate' => $cohortFirstTryRate,
            'extra_hours' => $studentMetrics['extra_hours'],
            'cohort_avg_extra_hours' => $cohortAvgExtraHours,
            'best_score' => $studentMetrics['best_score'],
            'cohort_avg_best_score' => $cohortAvgBestScore,
            'average_score' => $studentMetrics['average_score'],
            'cohort_avg_score' => $cohortAvgScore,
            'top_percent' => $topPercent,
        ];
    }

    private function extractEnrollmentMetrics(Enrollment $enrollment): array
    {
        $internalPracticeExam = $enrollment->exams
            ->where('type', 'internal')
            ->where('category', 'practice')
            ->where('result', 'passed')
            ->sortBy('date')
            ->first();

        $durationDays = null;
        if ($internalPracticeExam && $enrollment->enrolled_at) {
            $start = Carbon::parse($enrollment->enrolled_at)->startOfDay();
            $end = Carbon::parse($internalPracticeExam->date)->startOfDay();
            $durationDays = (int) abs($start->diffInDays($end));
        }

        $wordPracticeExams = $enrollment->exams
            ->where('type', 'state')
            ->where('category', 'practice');

        $wordAttempts = $wordPracticeExams->count();
        $wordPassed = $wordPracticeExams->where('result', 'passed')->isNotEmpty();

        $completedLessons = $enrollment->lessons->where('status', 'completed');

        $extraMinutes = 0;
        $scores = [];

        foreach ($completedLessons as $lesson) {
            $scores[] = $this->calculateScore($lesson);

            if ($lesson->is_additional_hour) {
                $start = Carbon::parse($lesson->start_time);
                $end = Carbon::parse($lesson->end_time);
                $extraMinutes += abs($start->diffInMinutes($end));
            }
        }

        $extraHours = round($extraMinutes / 60, 1);
        $bestScore = count($scores) > 0 ? max($scores) : null;
        $averageScore = count($scores) > 0 ? (int) round(array_sum($scores) / count($scores)) : null;

        return [
            'duration_days' => $durationDays,
            'word_attempts' => $wordAttempts,
            'word_passed' => $wordPassed,
            'extra_hours' => $extraHours,
            'best_score' => $bestScore,
            'average_score' => $averageScore,
        ];
    }

    private function getFeedbackMessage(int $score, ?int $average): string
    {
        if ($average === null) {
            return "Twój wynik to {$score}/100 pkt. Trzymaj tak dalej!";
        }

        if ($score >= $average + 5) {
            return "Świetna robota! Twój wynik to {$score}/100 pkt. Należysz do czołówki – idzie Ci zauważalnie lepiej niż średnia w Twojej grupie ({$average}/100).";
        } elseif ($score <= $average - 5) {
            return "Głowa do góry! Twój wynik to {$score}/100 pkt (średnia grupy: {$average}/100). Przeanalizuj z instruktorem popełnione błędy, następnym razem będzie lepiej!";
        } else {
            return "Dobra jazda! Twój wynik to {$score}/100 pkt. Trzymasz solidny poziom grupy (średnia: {$average}/100).";
        }
    }

    private function getLessonNumber(Lesson $lesson): int
    {
        return Lesson::where('enrollment_id', $lesson->enrollment_id)
            ->where('status', 'completed')
            ->where(function ($query) use ($lesson) {
                $query->where('date', '<', $lesson->date)
                      ->orWhere(function ($q) use ($lesson) {
                          $q->where('date', $lesson->date)
                            ->where('start_time', '<=', $lesson->start_time);
                      });
            })
            ->count();
    }
}
