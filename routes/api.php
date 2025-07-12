<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CourseController;

// 🔐 Auth routes (public)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register/parent', [AuthController::class, 'registerParent'])->name('register.parent');
Route::get('/courses/all', [CourseController::class, 'index'])->name('courses.index');
// 🔐 Authenticated routes (need token)
Route::middleware('auth:api')->group(function () {

    // 🔓 Logout & authenticated user info
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/me', [AuthController::class, 'me'])->name('me');

    // 👨‍👩‍👧 Parent actions
    Route::get('/parent/{userId}', [AuthController::class, 'showParent'])->name('parent.show');
    Route::post('/parent/{userId}/create-student', [AuthController::class, 'createStudent'])->name('parent.create-student');
    Route::post('/parent/{userId}/become-tutor', [AuthController::class, 'becomeTutor'])->name('parent.become-tutor');
    // 🎓 Student actions
    Route::get('/student/{studentProfile}', [AuthController::class, 'showStudent'])->name('student.show');


    // courses actions
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
});


