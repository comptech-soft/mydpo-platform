<?php

use MyDpo\Http\Controllers\Auth\UsersStatusesController;

Route::prefix('users')->group( function() {
        
    Route::post('items', [UsersStatusesController::class, 'getItems']);
    
    
});