<?php

use MyDpo\Http\Controllers\Admin\UsersController;

Route::prefix('utilizatori')->group( function() {
        
    Route::get('/', [UsersController::class, 'index']);        
    Route::post('items', [UsersController::class, 'getItems']);

    /**
     * Asta la ce e buna? 
     */
    // Route::post('persons/items', [CustomersController::class, 'getItemsWithPersons']);
    
    // Route::post('action/{action}', [CustomersController::class, 'doAction']);
});