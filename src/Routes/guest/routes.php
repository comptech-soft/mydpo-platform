<?php

use MyDpo\Http\Controllers\Usersession\LoginController;
use MyDpo\Http\Controllers\Auth\AuthenticatedSessionController;

Route::middleware('guest')->group(function () {

    Route::get('login',  [LoginController::class, 'index']);
    Route::post('system/login',  [LoginController::class, 'login']);

    Route::get('forgot-password',  [ForgotPasswordController::class, 'index']);
    Route::post('system/forgot-password',  [ForgotPasswordController::class, 'sendResetPasswordLink']);
    
    /**
     * Atentie la asta! Trebuie pentru SSO
     */
    Route::get('connect', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

});