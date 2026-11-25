<?php

use App\Http\Controllers\Admin\CourseController;
use App\Http\Controllers\Admin\EnrollmentController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\InstructorAvailabilityController;
use App\Http\Controllers\Admin\LessonController as AdminLessonController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Instructor\AvailabilityController;
use App\Http\Controllers\Instructor\LessonController as InstructorLessonController;
use App\Http\Controllers\PublicController;
use App\Http\Controllers\Student\EnrollmentController as StudentEnrollmentController;
use App\Http\Controllers\Student\ResultController;
use App\Http\Controllers\Student\LessonController as StudentLessonController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsInstructor;
use App\Http\Middleware\IsStudent;
use Illuminate\Support\Facades\Route;

Route::get('/', [PublicController::class, 'home'])->name('home');
Route::get('/kursy', [PublicController::class, 'courses'])->name('public.courses');
Route::get('/flota', [PublicController::class, 'fleet'])->name('public.fleet');
Route::get('/o-nas', [PublicController::class, 'about'])->name('public.about');
Route::get('/kontakt', [PublicController::class, 'contact'])->name('public.contact');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth', IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', CourseController::class);
    Route::resource('vehicles', VehicleController::class);
    Route::resource('users', UserController::class);
    Route::patch('enrollments/{enrollment}/approve', [EnrollmentController::class, 'approve'])->name('enrollments.approve');
    Route::patch('enrollments/{enrollment}/reject', [EnrollmentController::class, 'reject'])->name('enrollments.reject');
    Route::resource('enrollments', EnrollmentController::class);
    Route::resource('lessons', AdminLessonController::class);
    Route::resource('instructor-availabilities', InstructorAvailabilityController::class);
    Route::resource('exams', ExamController::class);
});

Route::middleware(['auth', IsInstructor::class])->prefix('instructor')->name('instructor.')->group(function () {
    Route::get('/availability', [AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [AvailabilityController::class, 'store'])->name('availability.store');
    Route::delete('/availability/{availability}', [AvailabilityController::class, 'destroy'])->name('availability.destroy');

    Route::get('/lessons', [InstructorLessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson}/evaluate', [InstructorLessonController::class, 'evaluate'])->name('lessons.evaluate');
    Route::put('/lessons/{lesson}/evaluate', [InstructorLessonController::class, 'updateEvaluation'])->name('lessons.updateEvaluation');
});

Route::middleware(['auth', IsStudent::class])->prefix('student')->name('student.')->group(function () {
    Route::get('/results', [ResultController::class, 'index'])->name('results.index');
    Route::post('/enrollments', [StudentEnrollmentController::class, 'store'])->name('enrollments.store');
    Route::get('/lessons/book', [StudentLessonController::class, 'create'])->name('lessons.create');
    Route::post('/lessons/book', [StudentLessonController::class, 'store'])->name('lessons.store');
});

require __DIR__.'/auth.php';
