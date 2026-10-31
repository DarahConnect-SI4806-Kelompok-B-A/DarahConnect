<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\SessionController;
use App\Http\Controllers\Auth\WorkspaceController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/register', [RegisterController::class, 'create'])->name('auth.register');
    Route::post('/register', [RegisterController::class, 'store'])->name('auth.register.store');

    Route::get('/login', [SessionController::class, 'create'])->name('auth.login');
    Route::post('/login', [SessionController::class, 'store'])->name('auth.login.store');
});

Route::middleware(['auth', 'role'])->group(function () {
    Route::get('/workspace', [WorkspaceController::class, 'index'])->name('dashboard');

    Route::post('/logout', [SessionController::class, 'destroy'])->name('auth.logout');
    Route::delete('/sessions/others', [SessionController::class, 'destroyOtherSessions'])->name('auth.sessions.destroy-others');
    Route::delete('/sessions/{sessionId}', [SessionController::class, 'destroySession'])->name('auth.sessions.destroy');
});
