<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Exam;
use App\Models\InstructorAvailability;
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

            $admin = User::firstOrCreate(
                ['email' => 'admin@admin.com'],
                ['name' => 'Admin', 'surname' => 'Szkoły', 'phone' => '111222333', 'password' => 'password', 'role' => 'admin']
            );

            $mainInstructor = User::firstOrCreate(
                ['email' => 'instruktor@instruktor.com'],
                ['name' => 'Instruktor', 'surname' => 'Szkoły', 'phone' => '333222111', 'password' => 'password', 'role' => 'instructor', 'license_number' => 'INS1234']
            );

            $kursant1 = User::firstOrCreate(
                ['email' => 'kursant1@kursant1.com'],
                ['name' => 'Kursant1', 'surname' => 'Szkoły', 'phone' => '222111333', 'password' => 'password', 'role' => 'student']
            );

            $kursant2 = User::firstOrCreate(
                ['email' => 'kursant2@kursant2.com'],
                ['name' => 'Kursant2', 'surname' => 'Szkoły', 'phone' => '444111222', 'password' => 'password', 'role' => 'student']
            );

            $bVehiclesActive = Vehicle::factory(7)->create(['category' => 'B']);
            $bVehicleMaint = Vehicle::factory()->maintenance()->create(['category' => 'B']);
            $cVehiclesActive = Vehicle::factory(3)->categoryC()->create();
            $aVehiclesActive = Vehicle::factory(3)->categoryA()->create();
            $aVehicleMaint = Vehicle::factory()->categoryA()->maintenance()->create();

            $mainInstructor->update(['assigned_vehicle_id' => $bVehiclesActive[0]->id]);

            $instructorsB = User::factory(3)->instructor()->create();
            foreach ($instructorsB as $i => $inst) {
                $inst->update(['assigned_vehicle_id' => $bVehiclesActive[$i + 1]->id]);
            }

            $instructorC = User::factory()->instructor()->create(['assigned_vehicle_id' => $cVehiclesActive[0]->id]);
            $instructorA = User::factory()->instructor()->create(['assigned_vehicle_id' => $aVehiclesActive[0]->id]);

            $allInstructors = collect([$mainInstructor])->merge($instructorsB)->push($instructorC)->push($instructorA);

            $startDate = Carbon::today()->subMonths(6);
            $endDate = Carbon::today()->addMonths(2);

            foreach ($allInstructors as $index => $instructor) {
                $currentDate = $startDate->copy();

                while ($currentDate->lte($endDate)) {
                    if ($instructor->id === $mainInstructor->id && $currentDate->isWeekday()) {
                        InstructorAvailability::factory()->create(['instructor_id' => $instructor->id, 'date' => $currentDate, 'start_time' => '10:00:00', 'end_time' => '18:00:00']);
                    } elseif ($index === 1 || $index === 2) {
                        if ($currentDate->isWeekday()) InstructorAvailability::factory()->create(['instructor_id' => $instructor->id, 'date' => $currentDate, 'start_time' => '08:00:00', 'end_time' => '16:00:00']);
                    } elseif ($index === 3 || $index === 4) {
                        if ($currentDate->isWeekday()) InstructorAvailability::factory()->create(['instructor_id' => $instructor->id, 'date' => $currentDate, 'start_time' => '12:00:00', 'end_time' => '20:00:00']);
                    } elseif ($index === 5) {
                        if ($currentDate->dayOfWeek >= 3 || $currentDate->dayOfWeek == 0) InstructorAvailability::factory()->create(['instructor_id' => $instructor->id, 'date' => $currentDate, 'start_time' => '10:00:00', 'end_time' => '18:00:00']);
                    }
                    $currentDate->addDay();
                }
            }

            $courses = collect([
                Course::factory()->categoryB()->standard()->create(['start_date' => today()->subMonths(6)->toDateString()]),
                Course::factory()->categoryB()->standard()->create(['start_date' => today()->subDays(30)->toDateString()]),
                Course::factory()->categoryB()->accelerated()->create(['start_date' => today()->subDays(21)->toDateString()]),
                Course::factory()->categoryA()->standard()->create(['start_date' => today()->subDays(28)->toDateString()]),
                Course::factory()->categoryC()->standard()->create(['start_date' => today()->subMonths(5)->toDateString()]),
                Course::factory()->categoryB()->weekend()->upcoming()->create(['start_date' => today()->addDays(14)->toDateString()]),
            ]);

            $bookedSlots = [];

            $findAvailableSlot = function ($instructorId, Carbon $afterDate, bool $isAccelerated) use (&$bookedSlots, $allInstructors) {
                $daysToAdd = $isAccelerated ? 1 : rand(1, 3);
                $searchDate = $afterDate->copy()->addDays($daysToAdd);
                $instIndex = $allInstructors->search(fn($i) => $i->id === $instructorId);

                while (true) {
                    $isWorkingDay = ($instIndex === 5)
                        ? ($searchDate->dayOfWeek >= 3 || $searchDate->dayOfWeek === 0)
                        : $searchDate->isWeekday();

                    if ($isWorkingDay) {
                        $hours = match ($instIndex) {
                            1, 2 => [8, 10, 12, 14],
                            3, 4 => [12, 14, 16, 18],
                            default => [10, 12, 14, 16],
                        };
                        shuffle($hours);

                        foreach ($hours as $h) {
                            $key = "{$instructorId}_{$searchDate->format('Y-m-d')}_{$h}";
                            if (!isset($bookedSlots[$key])) {
                                $bookedSlots[$key] = true;
                                return ['date' => $searchDate->copy(), 'hour' => $h];
                            }
                        }
                    }
                    $searchDate->addDay();
                }
            };

            foreach ($courses as $cIndex => $course) {
                $isCompletedCourse = in_array($cIndex, [0, 4]);
                $isUpcomingCourse = ($cIndex === 5);
                $isAccelerated = ($course->type === 'accelerated');

                $courseInstructors = $allInstructors->filter(fn($i) => $i->assignedVehicle->category === $course->category)->values();
                if ($courseInstructors->isEmpty()) continue;

                for ($s = 0; $s < 10; $s++) {
                    if ($cIndex === 0 && $s === 0) {
                        $student = $kursant1;
                    } elseif ($cIndex === 1 && $s === 0) {
                        $student = $kursant2;
                    } else {
                        $student = User::factory()->create();
                    }

                    $archetype = 'average';
                    if ($s < 2) $archetype = 'excellent';
                    elseif ($s >= 8) $archetype = 'struggler';

                    $isDropped = ($archetype === 'struggler' && !$isCompletedCourse && !$isUpcomingCourse && $s === 9);

                    $enrollStatus = 'in_progress';
                    if ($isCompletedCourse) $enrollStatus = 'completed';
                    if ($isDropped) $enrollStatus = 'dropped';

                    $enrollment = Enrollment::factory()->create([
                        'user_id' => $student->id,
                        'course_id' => $course->id,
                        'status' => $enrollStatus,
                        'enrolled_at' => $course->start_date,
                    ]);

                    if ($isUpcomingCourse) continue;

                    $theoryDate = Carbon::parse($course->start_date)->addDays(14);
                    $enrollment->update(['theory_completed_at' => $theoryDate]);
                    Exam::factory()->create(['enrollment_id' => $enrollment->id, 'date' => $theoryDate]);

                    $instructor = $courseInstructors->random();
                    $requiredLessons = (int) ceil($course->required_practice_hours / 2);

                    $extraLessons = 0;
                    if ($isCompletedCourse) {
                        if ($archetype === 'average') $extraLessons = rand(0, 1);
                        if ($archetype === 'struggler') $extraLessons = rand(1, 2);
                    }

                    $totalLessonsToGenerate = $requiredLessons + $extraLessons;


                    if ($isDropped) {
                        $totalLessonsToGenerate = rand(3, 7);
                    }

                    $lastLessonDate = null;
                    $lessonTrackerDate = $theoryDate->copy();

                    for ($l = 0; $l < $totalLessonsToGenerate; $l++) {
                        $slot = $findAvailableSlot($instructor->id, $lessonTrackerDate, $isAccelerated);
$lDate = $slot['date'];
$lHour = $slot['hour'];
$lessonTrackerDate = $lDate->copy();

$lessonEnd = $lDate->copy()->setTime($lHour + 2, 0);
$lessonStatus = $lessonEnd->isPast() ? 'completed' : 'scheduled';
if ($isDropped && $lessonEnd->isFuture()) continue;
                        $isExtra = ($l >= $requiredLessons);

                        $factoryState = null;
                        if ($archetype === 'excellent' && $l >= 3) $factoryState = 'perfectRide';
                        if ($archetype === 'average') {
                            if (in_array($l, [4, 8])) $factoryState = 'terribleRide';
                            if (in_array($l, [5, 9])) $factoryState = 'perfectRide';
                        }
                        if ($archetype === 'struggler') {
                            if ($l % 3 === 0) $factoryState = 'terribleRide';
                        }
                        if ($lessonStatus === 'scheduled') $factoryState = 'scheduled';

                        $lessonFactory = Lesson::factory();


                        if ($factoryState) {
                            $lessonFactory = $lessonFactory->{$factoryState}();
                        }

                        $lesson = $lessonFactory->create([
                            'enrollment_id' => $enrollment->id,
                            'instructor_id' => $instructor->id,
                            'vehicle_id' => $instructor->assigned_vehicle_id,
                            'booked_by_id' => $student->id,
                            'date' => $lDate->toDateString(),
                            'start_time' => sprintf('%02d:00:00', $lHour),
                            'end_time' => sprintf('%02d:00:00', $lHour + 2),
                            'is_additional_hour' => $isExtra,
                            'status' => $lessonStatus,
                        ]);

                        $lastLessonDate = $lDate->copy();
                    }

                    if ($isCompletedCourse && $lastLessonDate) {
                        $enrollment->update(['practice_completed_at' => $lastLessonDate]);

                        $internalPracDate = $lastLessonDate->copy()->addDays(3);
                        Exam::factory()->practice()->create(['enrollment_id' => $enrollment->id, 'instructor_id' => $instructor->id, 'date' => $internalPracDate]);

                        $stateTheoryDate = $internalPracDate->copy()->addDays(10);
                        Exam::factory()->stateExam()->create(['enrollment_id' => $enrollment->id, 'date' => $stateTheoryDate]);

                        $statePracDate = $stateTheoryDate->copy()->addDays(7);

                        if ($archetype === 'excellent') {
                            Exam::factory()->stateExam()->practice()->create(['enrollment_id' => $enrollment->id, 'date' => $statePracDate]);
                        } elseif ($archetype === 'average') {
                            if (rand(0, 1)) {
                                Exam::factory()->stateExam()->practice()->failed()->create(['enrollment_id' => $enrollment->id, 'date' => $statePracDate]);
                                Exam::factory()->stateExam()->practice()->create(['enrollment_id' => $enrollment->id, 'date' => $statePracDate->addDays(14)]);
                            } else {
                                Exam::factory()->stateExam()->practice()->create(['enrollment_id' => $enrollment->id, 'date' => $statePracDate]);
                            }
                        } elseif ($archetype === 'struggler') {
                            Exam::factory()->stateExam()->practice()->failed()->create(['enrollment_id' => $enrollment->id, 'date' => $statePracDate]);
                            Exam::factory()->stateExam()->practice()->failed()->create(['enrollment_id' => $enrollment->id, 'date' => $statePracDate->copy()->addDays(14)]);
                            Exam::factory()->stateExam()->practice()->create(['enrollment_id' => $enrollment->id, 'date' => $statePracDate->copy()->addDays(28)]);
                        }
                    }
                }
            }
        });
    }
}
