<?php

// use MyDpo\Http\Controllers\Usersession\ForgotPasswordController;
// use MyDpo\Http\Controllers\Usersession\NewPasswordController;

// use MyDpo\Http\Controllers\Auth\AuthenticatedSessionController;

// Route::middleware('guest')->group(function () {

//     /**
//      * Resetarea parolei
//      */
//     Route::get('',  [ForgotPasswordController::class, '']);
//     Route::post('',  [ForgotPasswordController::class, '']);
//     Route::get('', [NewPasswordController::class, 'index'])->name('');
//     Route::post('', [NewPasswordController::class, '']);


//     /**
//      * Atentie la asta! Trebuie pentru SSO
//      */
//     Route::get('connect', [AuthenticatedSessionController::class, 'create'])->name('login');
//     Route::post('login', [AuthenticatedSessionController::class, 'store']);

// });