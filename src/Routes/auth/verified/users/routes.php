<?php

use MyDpo\Http\Controllers\Auth\UsersController;

Route::prefix('users')->group( function() {
        
    Route::post('items', [UsersController::class, 'getItems']);
});