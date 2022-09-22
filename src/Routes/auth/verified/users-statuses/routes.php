<?php

use MyDpo\Http\Controllers\Auth\UsersStatusesController;

Route::prefix('users-statuses')->group( function() {
        
    Route::post('items', [UsersStatusesController::class, 'getItems']);
    
    
});