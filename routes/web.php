<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Student\DashboardController as StudentDashboard;
use App\Http\Controllers\Student\HoursController;
use App\Http\Controllers\Student\ReportController;
use App\Http\Controllers\Supervisor\DashboardController as SupervisorDashboard;
use App\Http\Controllers\Coordinator\DashboardController as CoordinatorDashboard;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::middleware('auth.session')->group(function () {
    Route::get('/change-password', [AuthController::class, 'showChangePassword'])->name('change-password');
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('student')->name('student.')->middleware('role:student')->group(function () {
        Route::get('/dashboard', [StudentDashboard::class, 'index'])->name('dashboard');
        Route::get('/log-hours', [HoursController::class, 'index'])->name('log-hours');
        Route::post('/log-hours', [HoursController::class, 'store']);
        Route::get('/submit-reports', [ReportController::class, 'index'])->name('submit-reports');
        Route::post('/submit-reports', [ReportController::class, 'store']);
    });

    Route::prefix('supervisor')->name('supervisor.')->middleware('role:supervisor')->group(function () {
        Route::get('/dashboard', [SupervisorDashboard::class, 'index'])->name('dashboard');
    });

    Route::prefix('coordinator')->name('coordinator.')->middleware('role:coordinator')->group(function () {
        Route::get('/dashboard', [CoordinatorDashboard::class, 'index'])->name('dashboard');
    });
});
