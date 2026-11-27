<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\InstructorAvailability;
use App\Http\Requests\Instructor\StoreAvailabilityRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class AvailabilityController extends Controller
{
  public function index(Request $request): View
    {
        $date = $request->filled('date') ? (string) $request->query('date') : null;
        $upcoming = $request->boolean('upcoming');

        $query = InstructorAvailability::where('instructor_id', Auth::id());

        if ($date) {
            $query->where('date', $date);
        }

        if ($upcoming) {
            $query->where('date', '>=', Carbon::today()->toDateString());
        }

        $availabilities = $query->orderBy('date', $upcoming ? 'asc' : 'desc')
            ->orderBy('start_time', 'asc')
            ->paginate(15)
            ->withQueryString();

        return view('instructor.availability.index', compact('availabilities', 'date', 'upcoming'));
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
