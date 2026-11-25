<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreLessonRequest;
use App\Models\Enrollment;
use App\Models\InstructorAvailability;
use App\Models\Lesson;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class LessonController extends Controller
{
    public function create(Request $request): View
    {
        $eligibleEnrollments = Enrollment::with(['course', 'lessons'])
            ->where('user_id', Auth::id())
            ->where('status', 'in_progress')
            ->whereHas('exams', function ($query) {
                $query->where('type', 'internal')
                      ->where('category', 'theory')
                      ->where('result', 'passed');
            })
            ->get();

        $selectedEnrollment = null;
        if ($eligibleEnrollments->isNotEmpty()) {
            $requestedEnrollmentId = (int) $request->query('enrollment_id');
            $selectedEnrollment = $eligibleEnrollments->firstWhere('id', $requestedEnrollmentId)
                ?? $eligibleEnrollments->first();
        }

        $instructors = collect();
        $selectedInstructor = null;
        $minDate = Carbon::tomorrow()->toDateString();
        $selectedDate = $request->query('date', $minDate);

        if ($selectedDate < $minDate) {
            $selectedDate = $minDate;
        }

        $availableSlots = [];
        $searched = false;
        $willBeAdditionalHour = false;

        if ($selectedEnrollment) {
            $courseCategory = $selectedEnrollment->course->category;

            $instructors = User::where('role', 'instructor')
                ->whereHas('assignedVehicle', function ($query) use ($courseCategory) {
                    $query->where('category', $courseCategory)
                          ->where('status', 'active')
                          ->whereNull('deleted_at');
                })
                ->with('assignedVehicle')
                ->orderBy('surname')
                ->get();

            $usedMinutes = 0;
            foreach ($selectedEnrollment->lessons->whereIn('status', ['completed', 'scheduled']) as $lesson) {
                $usedMinutes += abs(Carbon::parse($lesson->start_time)->diffInMinutes(Carbon::parse($lesson->end_time)));
            }
            $willBeAdditionalHour = ($usedMinutes / 60) >= $selectedEnrollment->course->required_practice_hours;

            $requestedInstructorId = (int) $request->query('instructor_id');
            $selectedInstructor = $instructors->firstWhere('id', $requestedInstructorId);

            if ($selectedInstructor && $request->filled('date')) {
                $searched = true;

                $availabilities = InstructorAvailability::where('instructor_id', $selectedInstructor->id)
                    ->where('date', $selectedDate)
                    ->orderBy('start_time')
                    ->get();

                $dayLessons = Lesson::where('date', $selectedDate)
                    ->where('status', '!=', 'cancelled')
                    ->where(function ($query) use ($selectedInstructor) {
                        $query->where('instructor_id', $selectedInstructor->id)
                              ->orWhere('vehicle_id', $selectedInstructor->assigned_vehicle_id)
                              ->orWhere('booked_by_id', Auth::id());
                    })
                    ->get();

                foreach ($availabilities as $availability) {
                    $slotStart = Carbon::parse($selectedDate . ' ' . $availability->start_time);
                    $blockEnd = Carbon::parse($selectedDate . ' ' . $availability->end_time);

                    while ($slotStart->copy()->addHours(2)->lte($blockEnd)) {
                        $slotEnd = $slotStart->copy()->addHours(2);
                        $sStartStr = $slotStart->format('H:i');
                        $sEndStr = $slotEnd->format('H:i');

                        $hasCollision = $dayLessons->contains(function ($lesson) use ($sStartStr, $sEndStr) {
                            $lStart = substr($lesson->start_time, 0, 5);
                            $lEnd = substr($lesson->end_time, 0, 5);
                            return $lStart < $sEndStr && $lEnd > $sStartStr;
                        });

                        if (!$hasCollision) {
                            $availableSlots[] = [
                                'start_time' => $sStartStr,
                                'end_time' => $sEndStr,
                            ];
                        }

                        $slotStart->addHours(2);
                    }
                }
            }
        }

        return view('student.lessons.create', compact(
            'eligibleEnrollments',
            'selectedEnrollment',
            'instructors',
            'selectedInstructor',
            'selectedDate',
            'minDate',
            'availableSlots',
            'searched',
            'willBeAdditionalHour'
        ));
    }

    public function store(StoreLessonRequest $request): RedirectResponse
    {
        $data = $request->validated();

        $enrollment = Enrollment::with(['course', 'lessons'])
            ->where('user_id', Auth::id())
            ->findOrFail($data['enrollment_id']);

        $instructor = User::where('role', 'instructor')
            ->findOrFail($data['instructor_id']);

        $usedMinutes = 0;
        foreach ($enrollment->lessons->whereIn('status', ['completed', 'scheduled']) as $lesson) {
            $usedMinutes += abs(Carbon::parse($lesson->start_time)->diffInMinutes(Carbon::parse($lesson->end_time)));
        }
        $isAdditionalHour = ($usedMinutes / 60) >= $enrollment->course->required_practice_hours;

        Lesson::create([
            'enrollment_id' => $enrollment->id,
            'instructor_id' => $instructor->id,
            'vehicle_id' => $instructor->assigned_vehicle_id,
            'booked_by_id' => Auth::id(),
            'is_additional_hour' => $isAdditionalHour,
            'date' => $data['date'],
            'start_time' => $data['start_time'] . ':00',
            'end_time' => $data['end_time'] . ':00',
            'status' => 'scheduled',
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Jazda została pomyślnie zarezerwowana.');
    }
}
