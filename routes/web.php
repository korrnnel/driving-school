<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('courses', App\Http\Controllers\Admin\CourseController::class);
    Route::resource('vehicles', App\Http\Controllers\Admin\VehicleController::class);
    Route::resource('users', App\Http\Controllers\Admin\UserController::class);
    Route::resource('enrollments', App\Http\Controllers\Admin\EnrollmentController::class);
    Route::resource('lessons', App\Http\Controllers\Admin\LessonController::class);
    Route::resource('instructor-availabilities', App\Http\Controllers\Admin\InstructorAvailabilityController::class);
});

require __DIR__.'/auth.php';
