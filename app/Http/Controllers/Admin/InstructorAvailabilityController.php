<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorAvailability;
use App\Models\User;
use App\Http\Requests\StoreInstructorAvailabilityRequest;
use App\Http\Requests\UpdateInstructorAvailabilityRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Http\Request;

class InstructorAvailabilityController extends Controller
{
    public function index(Request $request): View
    {
        $instructorId = $request->filled('instructor_id') ? (int) $request->query('instructor_id') : null;
        $date = $request->filled('date') ? (string) $request->query('date') : null;

        $query = InstructorAvailability::with('instructor');

        if ($instructorId) {
            $query->where('instructor_id', $instructorId);
        }

        if ($date) {
            $query->where('date', $date);
        }

        $availabilities = $query->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        $instructors = User::where('role', 'instructor')->orderBy('surname')->get();

        return view('admin.instructor-availabilities.index', compact('availabilities', 'instructors', 'instructorId', 'date'));
    }

    public function create(): View
    {
        $instructors = User::where('role', 'instructor')->orderBy('surname')->get();
        return view('admin.instructor-availabilities.create', compact('instructors'));
    }

    public function store(StoreInstructorAvailabilityRequest $request): RedirectResponse
    {
        InstructorAvailability::create($request->validated());

        return redirect()->route('admin.instructor-availabilities.index')
            ->with('success', 'Grafik instruktora został pomyślnie dodany.');
    }

    public function show(InstructorAvailability $instructorAvailability): View
    {
        $instructorAvailability->load('instructor');
        return view('admin.instructor-availabilities.show', compact('instructorAvailability'));
    }

    public function edit(InstructorAvailability $instructorAvailability): View
    {
        $instructors = User::withTrashed()
            ->where('role', 'instructor')
            ->where(function($query) use ($instructorAvailability) {
                $query->whereNull('deleted_at')
                      ->orWhere('id', $instructorAvailability->instructor_id);
            })
            ->orderBy('surname')
            ->get();

        return view('admin.instructor-availabilities.edit', compact('instructorAvailability', 'instructors'));
    }

    public function update(UpdateInstructorAvailabilityRequest $request, InstructorAvailability $instructorAvailability): RedirectResponse
    {
        $instructorAvailability->update($request->validated());

        return redirect()->route('admin.instructor-availabilities.index')
            ->with('success', 'Grafik instruktora został pomyślnie zaktualizowany.');
    }

    public function destroy(InstructorAvailability $instructorAvailability): RedirectResponse
    {
        $instructorAvailability->delete();

        return redirect()->route('admin.instructor-availabilities.index')
            ->with('success', 'Wpis z grafiku został usunięty.');
    }
}
