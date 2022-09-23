<?php

use MyDpo\Http\Controllers\Auth\UsersCustomersController;

Route::prefix('users-customers')->group( function() {
        
    Route::post('items', [UsersCustomersController::class, 'getItems']);
    
    
});