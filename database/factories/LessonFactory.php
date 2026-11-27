<?php

namespace Database\Factories;

use App\Models\Lesson;
use Illuminate\Database\Eloquent\Factories\Factory;

class LessonFactory extends Factory
{
    protected $model = Lesson::class;

    public function definition(): array
    {
        $planned = fake()->numberBetween(5, 10);

        return [
            'date' => fake()->dateTimeBetween('-3 months', 'now'),
            'status' => 'completed',
            'is_additional_hour' => false,
            'tasks_planned_count' => $planned,
            'tasks_completed_count' => fake()->numberBetween((int) ceil($planned * 0.7), $planned),
            'interventions_count' => 0,
            'critical_mistakes_count' => fake()->numberBetween(0, 2),
            'minor_mistakes_count' => fake()->numberBetween(1, 5),
            'instructor_notes' => $this->averageNote(),
        ];
    }

    public function perfectRide(): static
    {
        return $this->state(function (array $attributes) {
            $planned = fake()->numberBetween(5, 12);
            return [
                'tasks_planned_count' => $planned,
                'tasks_completed_count' => $planned,
                'interventions_count' => 0,
                'critical_mistakes_count' => 0,
                'minor_mistakes_count' => fake()->numberBetween(0, 2),
                'instructor_notes' => $this->perfectNote(),
            ];
        });
    }

    public function terribleRide(): static
    {
        return $this->state(function (array $attributes) {
            $planned = fake()->numberBetween(4, 7);
            return [
                'tasks_planned_count' => $planned,
                'tasks_completed_count' => fake()->numberBetween((int) ceil($planned * 0.5), (int) ceil($planned * 0.7)),
                'interventions_count' => 1,
                'critical_mistakes_count' => fake()->numberBetween(0, 1),
                'minor_mistakes_count' => fake()->numberBetween(2, 3),
                'instructor_notes' => $this->terribleNote(),
            ];
        });
    }

    public function noTasksRide(): static
    {
        return $this->state(fn (array $attributes) => [
            'tasks_planned_count' => 0,
            'tasks_completed_count' => 0,
        ]);
    }

    public function scheduled(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'scheduled',
            'tasks_planned_count' => 0,
            'tasks_completed_count' => 0,
            'interventions_count' => 0,
            'critical_mistakes_count' => 0,
            'minor_mistakes_count' => 0,
            'instructor_notes' => null,
        ]);
    }

    private function averageNote(): ?string
    {
        if (!fake()->boolean(40)) {
            return null;
        }

        $observations = [
            'Poprawna jazda w ruchu miejskim.',
            'Dobre tempo i ogólne opanowanie pojazdu.',
            'Manewry na placu wykonane poprawnie.',
            'Stabilna jazda na skrzyżowaniach równorzędnych.',
            'Poprawne parkowanie równoległe i prostopadłe.',
            'Dobra reakcja na sygnalizację świetlną.',
        ];

        $recommendations = [
            'Warto popracować nad płynnością zmiany biegów.',
            'Należy wcześniej sygnalizować zamiar skrętu kierunkowskazem.',
            'Do poprawy łagodniejsze hamowanie przed skrzyżowaniami.',
            'Trzeba częściej kontrolować lusterka przed zmianą pasa.',
            'Wymaga nieco pewniejszego ruszania pod górę.',
            'Dobry kierunek, warto dalej utrwalać jazdę po rondach.',
        ];

        return fake()->randomElement($observations) . ' ' . fake()->randomElement($recommendations);
    }

    private function perfectNote(): ?string
    {
        if (!fake()->boolean(35)) {
            return null;
        }

        $observations = [
            'Bardzo pewna i płynna jazda przez całą lekcję.',
            'Wszystkie zaplanowane manewry wykonane bezbłędnie.',
            'Wzorowa obserwacja drogi, znaków i pieszych.',
            'Świetne wyczucie pojazdu oraz kultura jazdy.',
            'Bardzo dobre radzenie sobie w gęstym ruchu miejskim.',
        ];

        $recommendations = [
            'Wysoka precyzja i spokój za kierownicą.',
            'Bardzo dobra dynamika oraz pewność wykonywanych manewrów.',
            'Bardzo wysoki poziom przygotowania praktycznego.',
            'Brak zastrzeżeń, jazda na poziomie egzaminacyjnym.',
            'Solidne opanowanie wszystkich elementów trasy.',
        ];

        return fake()->randomElement($observations) . ' ' . fake()->randomElement($recommendations);
    }

    private function terribleNote(): ?string
    {
        if (!fake()->boolean(80)) {
            return null;
        }

        $observations = [
            'Wymuszenie pierwszeństwa na skrzyżowaniu.',
            'Brak obserwacji lusterek podczas zmiany pasa ruchu.',
            'Zbyt duża prędkość przy dojeździe do przejścia dla pieszych.',
            'Nerwowe reakcje i problemy z oceną odległości na rondzie.',
            'Zignorowanie znaku pionowego przed wjazdem na skrzyżowanie.',
            'Niebezpieczne wykonanie manewru skrętu w lewo.',
        ];

        $recommendations = [
            'Konieczna interwencja instruktora, wracamy do podstaw.',
            'Wymaga spokojniejszego podejścia i powtórzenia przepisów.',
            'Wymaga dodatkowego przećwiczenia zasad na skrzyżowaniach.',
            'Należy popracować nad koncentracją i przewidywaniem sytuacji.',
            'Zalecono dodatkowe powtórzenie zasad pierwszeństwa.',
        ];

        return fake()->randomElement($observations) . ' ' . fake()->randomElement($recommendations);
    }
}
