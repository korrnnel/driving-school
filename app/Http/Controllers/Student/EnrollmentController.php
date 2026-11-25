<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\Student\StoreEnrollmentRequest;
use App\Models\Enrollment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class EnrollmentController extends Controller
{
    public function store(StoreEnrollmentRequest $request): RedirectResponse
    {
        $data = $request->validated();

        Enrollment::create([
            'user_id' => Auth::id(),
            'course_id' => $data['course_id'],
            'pkk_number' => $data['pkk_number'] ?? null,
            'status' => 'waiting',
            'enrolled_at' => now(),
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Zgłoszenie na kurs zostało wysłane i oczekuje na akceptację biura.');
    }
}
