<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            User::firstOrCreate(
                ['email' => 'admin@admin.com'],
                [
                    'name' => 'Admin',
                    'surname' => 'Szkoły',
                    'phone' => '111222333',
                    'password' => 'password',
                    'role' => 'admin',
                ]
            );

            $instructors = User::factory(4)->instructor()->withVehicle()->create();

            $motorcycle = Vehicle::factory()->categoryA()->create();
            $instructors->push(
                User::factory()->instructor()->withVehicle($motorcycle->id)->create()
            );

            $truck = Vehicle::factory()->categoryC()->create();
            $instructors->push(
                User::factory()->instructor()->withVehicle($truck->id)->create()
            );

            $courses = collect([
                Course::factory()->categoryB()->standard()->create([
                    'start_date' => today()->subDays(100)->toDateString(),
                ]),
                Course::factory()->categoryB()->accelerated()->upcoming()->create(),
                Course::factory()->categoryA()->standard()->create([
                    'start_date' => today()->subDays(90)->toDateString(),
                ]),
                Course::factory()->categoryC()->weekend()->unscheduled()->create(),
            ]);

            $students = User::factory(30)->create();
            $bookedSlots = [];

            foreach ($students as $index => $student) {
                $course = $courses[$index % $courses->count()];
                $baseDate = $course->start_date
                    ? Carbon::parse($course->start_date)
                    : null;

                $isCompleted = in_array($index, [0, 2, 4, 6], true);
                $isDropped = in_array($index, [8, 10], true);
                $status = $isCompleted ? 'completed' : ($isDropped ? 'dropped' : 'in_progress');

                $enrolledAt = $baseDate && $baseDate->isPast()
                    ? $baseDate->copy()->subDays(7)
                    : now()->subDays(7);

                $enrollment = Enrollment::create([
                    'user_id' => $student->id,
                    'course_id' => $course->id,
                    'status' => $status,
                    'enrolled_at' => $enrolledAt,
                    'theory_completed_at' => $isCompleted
                        ? $baseDate->copy()->addDays(14)->setTime(18, 0)
                        : null,
                ]);

                if (!$baseDate) {
                    continue;
                }

                $matchingInstructors = $instructors->filter(
                    fn ($user) => $user->assignedVehicle?->category === $course->category
                );

                if ($matchingInstructors->isEmpty()) {
                    throw new \RuntimeException("Brak instruktora dla kategorii {$course->category}");
                }

                $instructor = $matchingInstructors->random();

                $lessonCount = $isCompleted
                    ? (int) ceil($course->required_practice_hours / 2)
                    : rand(1, 3);

                $lastLessonEnd = null;

                for ($i = 0; $i < $lessonCount; $i++) {
                    $lessonDate = $isCompleted
                        ? $baseDate->copy()->addDays(16 + $i * 2)
                        : $baseDate->copy()->addDays(rand(1, $baseDate->isFuture() ? 30 : 60));

                    $startHour = $isCompleted
                        ? ($index < 4 ? 8 : 10)
                        : fake()->randomElement([8, 10, 12, 14, 16]);

                    $slotKey = "{$instructor->id}_{$lessonDate->format('Y-m-d')}_{$startHour}";

                    if (isset($bookedSlots[$slotKey])) {
                        if ($isCompleted) {
                            throw new \RuntimeException('Kolizja terminu ukończonego kursu.');
                        }
                        continue;
                    }

                    $lessonStart = $lessonDate->copy()->setTime($startHour, 0);
                    $lessonStatus = $isCompleted
                        ? 'completed'
                        : ($lessonStart->isPast()
                            ? fake()->randomElement(['completed', 'no_show', 'cancelled'])
                            : 'scheduled');

                    Lesson::create([
                        'enrollment_id' => $enrollment->id,
                        'instructor_id' => $instructor->id,
                        'vehicle_id' => $instructor->assigned_vehicle_id,
                        'booked_by_id' => $student->id,
                        'date' => $lessonDate->toDateString(),
                        'start_time' => sprintf('%02d:00:00', $startHour),
                        'end_time' => sprintf('%02d:00:00', $startHour + 2),
                        'status' => $lessonStatus,
                    ]);

                    $bookedSlots[$slotKey] = true;
                    $lastLessonEnd = $lessonDate->copy()->setTime($startHour + 2, 0);
                }

                if ($isCompleted) {
                    $enrollment->update(['practice_completed_at' => $lastLessonEnd]);
                }
            }
        });
    }
}
