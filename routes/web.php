<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Redirect root to events
Route::get('/', function () {
    return redirect()->route('events.index');
});

// Public Event Catalogue & Detail (Accessible by both Guests and Authenticated Users)
Route::get('/events', [EventController::class, 'index'])->name('events.index');

// Guest Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Admin & Panitia Event Management
    Route::middleware('role:admin,panitia')->group(function () {
        Route::get('/events/create', [EventController::class, 'create'])->name('events.create');
        Route::post('/events', [EventController::class, 'store'])->name('events.store');
        Route::get('/events/{event}/edit', [EventController::class, 'edit'])->name('events.edit');
        Route::put('/events/{event}', [EventController::class, 'update'])->name('events.update');
        Route::delete('/events/{event}', [EventController::class, 'destroy'])->name('events.destroy');

        // Manage Registrations (Admin & Panitia)
        Route::get('/manage-registrations', [RegistrationController::class, 'index'])->name('registrations.index');
        Route::patch('/registrations/{registration}/status', [RegistrationController::class, 'updateStatus'])->name('registrations.update-status');
        Route::patch('/registrations/{registration}/check-in', [RegistrationController::class, 'quickCheckIn'])->name('registrations.check-in');
    });

    // Peserta Registration Routes
    Route::post('/registrations', [RegistrationController::class, 'store'])->name('registrations.store');
    Route::get('/my-registrations', [RegistrationController::class, 'myRegistrations'])->name('registrations.my');
    Route::get('/registrations/{registration}/ticket', [RegistrationController::class, 'showTicket'])->name('registrations.ticket');
    Route::post('/registrations/{registration}/cancel', [RegistrationController::class, 'cancel'])->name('registrations.cancel');

    // Category Management (Admin only)
    Route::middleware('role:admin')->group(function () {
        Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::put('/categories/{category}', [CategoryController::class, 'update'])->name('categories.update');
        Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');

        // User Management (Admin only)
        Route::get('/users', [UserController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [UserController::class, 'updateRole'])->name('users.update-role');
        Route::delete('/users/{user}', [UserController::class, 'destroy'])->name('users.destroy');
    });

    // Notification Routes
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/read-all', [NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});

// Event detail page
Route::get('/events/{event}', [EventController::class, 'show'])->name('events.show');
