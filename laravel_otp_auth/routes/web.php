<?php

use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\StudentAuth;
use Illuminate\Support\Facades\Route;


use App\Http\Controllers\AttendanceController;

Route::middleware(['auth:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        // Attendance Routes
        Route::get('/attendances', [AttendanceController::class, 'create'])->name('admin.attendances.create');
        Route::post('/attendances', [AttendanceController::class, 'store'])->name('admin.attendances.store');
        Route::get('/attendances/reports', [AttendanceController::class, 'reports'])->name('admin.attendances.reports');
    });
});


Route::middleware(['auth:admin'])->group(function () {
    Route::prefix('admin')->group(function () {
        Route::get('/students', [AdminController::class, 'index'])->name('admin.students.index');
        Route::get('/students/create', [AdminController::class, 'create'])->name('admin.students.create');
        Route::post('/students', [AdminController::class, 'store'])->name('admin.students.store');
        Route::get('/students/{student}/edit', [AdminController::class, 'edit'])->name('admin.students.edit');
        Route::put('/students/{student}', [AdminController::class, 'update'])->name('admin.students.update');
        Route::delete('/students/{student}', [AdminController::class, 'destroy'])->name('admin.students.destroy');
        Route::post('/students/{student}/status', [AdminController::class, 'updateStatus'])->name('admin.students.status');
        Route::post('/students/send-email-alls', [AdminController::class, 'sendEmailToAll'])->name('admin.students.send-email-all.post');
        Route::get('/students/send-email-all', [AdminController::class, 'sendEmailToAll'])->name('admin.students.send-email-all');

        // Change this route from POST to GET
        Route::get('/students/{student}/send-email', [AdminController::class, 'sendEmailToStudent'])->name('admin.students.send-email');

        // Send the email (POST)
        Route::post('/students/{student}/send-email', [AdminController::class, 'sendEmailToStudent'])->name('admin.students.send-email.post');
    });
});

Route::post('/ajax', [AdminController::class, 'ajax'])->name('ajax');

Route::get('/admin/loginn', [AdminAuthController::class, 'showLoginForm'])->name('admin.lg');
Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login');
Route::post('/admin/logout', [AdminAuthController::class, 'logout'])->name('admin.logout');

use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index'])->name('home');
Route::get('/users/edit/{id}', [UserController::class, 'index'])->name('user.edit');
Route::delete('/users/delete/{id}', [UserController::class, 'delete'])->name('user.delete');

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('forgot.password');
Route::post('/send-otp', [AuthController::class, 'sendOTP'])->name('send.otp');
Route::get('/reset-password', [AuthController::class, 'showResetPasswordForm'])->name('reset.password');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('reset.password.submit');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard')->middleware(StudentAuth::class);

Route::get('/', function () {

    return view('welcome');
});
