<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorAvailability;
use App\Http\Requests\Instructor\StoreAvailabilityRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AvailabilityController extends Controller
{
    public function index(): View
    {
        $availabilities = InstructorAvailability::where('instructor_id', Auth::id())
            ->orderBy('date', 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(15);

        return view('instructor.availability.index', compact('availabilities'));
    }

    public function store(StoreAvailabilityRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['instructor_id'] = Auth::id();

        InstructorAvailability::create($data);

        return redirect()->route('instructor.availability.index')
            ->with('success', 'Godziny pracy zostały dodane do grafiku.');
    }

    public function destroy(InstructorAvailability $availability): RedirectResponse
    {
        if ($availability->instructor_id !== Auth::id()) {
            abort(403);
        }

        $availability->delete();

        return redirect()->route('instructor.availability.index')
            ->with('success', 'Wpis został usunięty z grafiku.');
    }
}
