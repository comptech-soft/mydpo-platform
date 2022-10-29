<?php

use MyDpo\Http\Controllers\Auth\UsersController;
use MyDpo\Http\Controllers\Admin\UserDashboardController;

Route::prefix('users')->group( function() {
        
    Route::post('items', [UsersController::class, 'getItems']);
    Route::post('action/{action}', [UsersController::class, 'doAction']);
    Route::post('change-password', [UsersController::class, 'changePassword']);
    Route::post('save-user-settings', [UsersController::class, 'saveUserSettings']);
    
});


Route::prefix('utilizator-dashboard')->group( function() {
        
    Route::get('/{source}/{user_id}/{customer_id?}', [UserDashboardController::class, 'index']);        
   
});