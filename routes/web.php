<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [App\Http\Controllers\PublicController::class, 'home'])->name('home');
Route::get('/kursy', [App\Http\Controllers\PublicController::class, 'courses'])->name('public.courses');
Route::get('/flota', [App\Http\Controllers\PublicController::class, 'fleet'])->name('public.fleet');
Route::get('/o-nas', [App\Http\Controllers\PublicController::class, 'about'])->name('public.about');
Route::get('/kontakt', [App\Http\Controllers\PublicController::class, 'contact'])->name('public.contact');

Route::get('/dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', \App\Http\Middleware\IsAdmin::class])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
    Route::resource('vehicles', App\Http\Controllers\Admin\VehicleController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('enrollments', App\Http\Controllers\Admin\EnrollmentController::class);
    Route::resource('lessons', App\Http\Controllers\Admin\LessonController::class);
    Route::resource('instructor-availabilities', App\Http\Controllers\Admin\InstructorAvailabilityController::class);
    Route::resource('exams', App\Http\Controllers\Admin\ExamController::class);
});


Route::middleware(['auth', \App\Http\Middleware\IsInstructor::class])->prefix('instructor')->name('instructor.')->group(function () {

    Route::get('/availability', [App\Http\Controllers\Instructor\AvailabilityController::class, 'index'])->name('availability.index');
    Route::post('/availability', [App\Http\Controllers\Instructor\AvailabilityController::class, 'store'])->name('availability.store');
    Route::delete('/availability/{availability}', [App\Http\Controllers\Instructor\AvailabilityController::class, 'destroy'])->name('availability.destroy');

    Route::get('/lessons', [App\Http\Controllers\Instructor\LessonController::class, 'index'])->name('lessons.index');
    Route::get('/lessons/{lesson}/evaluate', [App\Http\Controllers\Instructor\LessonController::class, 'evaluate'])->name('lessons.evaluate');
    Route::put('/lessons/{lesson}/evaluate', [App\Http\Controllers\Instructor\LessonController::class, 'updateEvaluation'])->name('lessons.updateEvaluation');
});

Route::middleware(['auth', \App\Http\Middleware\IsStudent::class])->prefix('student')->name('student.')->group(function () {
    Route::get('/results', [App\Http\Controllers\Student\ResultController::class, 'index'])->name('results.index');
});

require __DIR__.'/auth.php';
