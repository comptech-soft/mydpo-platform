<?php

use MyDpo\Http\Controllers\Admin\CustomersController;

Route::prefix('clienti')->group( function() {
        
    Route::get('/', [CustomersController::class, 'index']);        
    Route::post('items', [CustomersController::class, 'getItems']);
    Route::post('persons/items', [CustomersController::class, 'getItemsWithPersons']);
    Route::post('action/{action}', [CustomersController::class, 'doAction']);
});