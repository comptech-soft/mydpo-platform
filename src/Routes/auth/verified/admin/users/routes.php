<?php

use MyDpo\Http\Controllers\Admin\UsersController;
use MyDpo\Http\Controllers\Admin\UserDashboardController;

Route::prefix('utilizatori')->group( function() {
        
    Route::get('/', [UsersController::class, 'index']);        
    Route::post('items', [UsersController::class, 'getItems']);
    Route::post('action/{action}', [UsersController::class, 'doAction']);
    Route::post('update-password', [UsersController::class, 'updatePassword']);

    /**
     * Asta la ce e buna? 
     */
    // Route::post('persons/items', [CustomersController::class, 'getItemsWithPersons']);
    
    // 
});


Route::prefix('utilizator-dashboard')->group( function() {
        
    Route::get('/{user_id}', [UserDashboardController::class, 'index']);        
   
});