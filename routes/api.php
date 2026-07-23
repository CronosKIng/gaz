<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentProfileController;

Route::middleware(['auth'])->group(function () {
    // Students API
    Route::get('/students', [StudentController::class, 'getApiStudents'])->name('api.students');
    
    // Student Profile API
    Route::get('/student/payment/{reg}', [StudentProfileController::class, 'getPayment'])->name('api.payment');
    Route::get('/student/examinations/{reg}', [StudentProfileController::class, 'getExaminations'])->name('api.examinations');
    Route::get('/student/exam-results', [StudentProfileController::class, 'getExamResults'])->name('api.exam-results');
    Route::get('/student/attendance/{reg}', [StudentProfileController::class, 'getAttendance'])->name('api.attendance');
});
