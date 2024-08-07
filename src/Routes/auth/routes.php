<?php

use MyDpo\Http\Controllers\System\DashboardController;

use MyDpo\Http\Controllers\System\AccountInactiveController;
// use MyDpo\Http\Controllers\Usersession\EmailVerificationPromptController;
use MyDpo\Http\Controllers\Usersession\VerifyEmailController;
use MyDpo\Http\Controllers\Usersession\LogoutController;
use MyDpo\Http\Controllers\System\ConfigController;

/**
 * Authenticated routes
 */
Route::middleware('auth')->group(function () {

    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    if( config('app.platform') == 'b2b' )
    {
        
        Route::get('/cont-inactiv/{customer_id}', [AccountInactiveController::class, 'index'])->name('account.inactive');
    }

    /**
     * apare cand se acceseaza o ruta protejata de middelware [verified]
     */
    // Route::get('verify-email', [EmailVerificationPromptController::class, 'index'])->name('verification.notice');

    /**
     * Trimite emailul de confirmare, verificare email = activarea contului 
     */
    Route::middleware('throttle:6,1')->post('email/verification-notification', [VerifyEmailController::class, 'sendActivationEmail']);

    /** 
     * Se declenseaza din emailul de confirmare, verificare email = activarea contului 
     * Activeaza si face redirect pe [dashboard]
     */
    Route::middleware(['signed', 'throttle:6,1'])->get('verify-email/{id}/{hash}', [VerifyEmailController::class, 'index'])->name('verification.verify');

    Route::prefix('system')->group(function () {

        /**
         * Iesirea din platforma
         */
        // Route::post('logout', [LogoutController::class, 'logout']);

    });

    require __DIR__ . '/verified/routes.php';
});
