<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstructorAvailability;
use App\Models\User;
use App\Http\Requests\StoreInstructorAvailabilityRequest;
use App\Http\Requests\UpdateInstructorAvailabilityRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class InstructorAvailabilityController extends Controller
{
    public function index(): View
    {
        $availabilities = InstructorAvailability::with('instructor')
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        return view('admin.instructor-availabilities.index', compact('availabilities'));
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
        $instructors = User::where('role', 'instructor')->orderBy('surname')->get();
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
