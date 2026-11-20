<?php

namespace App\Services;

use App\Models\Enrollment;
use App\Models\Lesson;

class CohortAnalysisService
{
    /**
     * Główna metoda analizująca pojedynczą jazdę
     */
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

    /**
     * Oblicza wynik punktowy (0-100) dla konkretnej jazdy
     */
    public function calculateScore(Lesson $lesson): int
    {
        // 1. Pobranie wag z pliku konfiguracyjnego (lub wartości domyślne)
        $maxBase = config('gamification.max_base_score', 100);
        $penalties = config('gamification.penalties', [
            'minor_mistake' => 2,
            'critical_mistake' => 10,
            'intervention' => 20,
        ]);

        // 2. Baza: Skuteczność zadań (jeśli nie planowano zadań, kursant dostaje max za skuteczność)
        $planned = $lesson->tasks_planned_count;
        $completed = $lesson->tasks_completed_count;

        $baseScore = $planned > 0 ? ($completed / $planned) * $maxBase : $maxBase;

        // 3. Kary: Punkty ujemne za błędy
        $penaltyScore = ($lesson->minor_mistakes_count * $penalties['minor_mistake'])
                      + ($lesson->critical_mistakes_count * $penalties['critical_mistake'])
                      + ($lesson->interventions_count * $penalties['intervention']);

        // 4. Wynik końcowy (nie może spaść poniżej 0)
        return (int) max(0, round($baseScore - $penaltyScore));
    }

    /**
     * Oblicza średnią z grupy porównawczej (Kohorty) dla +- 1 lekcji na tym samym typie kursu
     */
    public function getCohortAverage(Lesson $lesson): ?int
    {
        $lessonNumber = $this->getLessonNumber($lesson);
        $minNumber = max(1, $lessonNumber - 1);
        $maxNumber = $lessonNumber + 1;

        $enrollment = $lesson->enrollment;
        $course = $enrollment->course;

        // Pobieramy WSZYSTKIE inne zapisy na kursy tej samej kategorii i tego samego typu
        $cohortEnrollments = Enrollment::whereHas('course', function($query) use ($course) {
                $query->where('category', $course->category)
                      ->where('type', $course->type);
            })
            ->where('id', '!=', $enrollment->id)
            ->with(['lessons' => function($q) {
                // Pobieramy tylko jazdy zakończone, sortując chronologicznie
                $q->where('status', 'completed')->orderBy('date', 'asc')->orderBy('start_time', 'asc');
            }])
            ->get();

        $cohortScores = [];

        // Przelatujemy przez uczniów w grupie i pobieramy wyniki z ich analogicznego etapu kursu
        foreach ($cohortEnrollments as $enr) {
            foreach ($enr->lessons as $index => $l) {
                $currentNum = $index + 1;

                // Jeśli to lekcja w przedziale (np. 4, 5 lub 6 jazda dla analizowanej 5 jazdy)
                if ($currentNum >= $minNumber && $currentNum <= $maxNumber) {
                    $cohortScores[] = $this->calculateScore($l);
                }
            }
        }

        if (count($cohortScores) === 0) {
            return null; // Brak wystarczających danych w systemie do porównania
        }

        return (int) round(array_sum($cohortScores) / count($cohortScores));
    }

    /**
     * Generuje komunikat grywalizacyjny (UX) dla kursanta
     */
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

    /**
     * Funkcja pomocnicza: Oblicza, która z kolei jest to jazda kursanta w ramach zapisu
     */
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
