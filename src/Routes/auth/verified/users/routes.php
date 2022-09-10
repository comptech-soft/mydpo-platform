<?php

use MyDpo\Http\Controllers\Auth\UsersController;

Route::prefix('users')->group( function() {
        
    Route::post('items', [UsersController::class, 'getItems']);
    Route::post('action/{action}', [UsersController::class, 'doAction']);
    Route::post('save-user-settings', [UsersController::class, 'saveUserSettings']);
    
});