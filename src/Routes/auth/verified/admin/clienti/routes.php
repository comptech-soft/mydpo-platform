<?php

use MyDpo\Http\Controllers\Admin\CustomersController;

Route::prefix('clienti')->group( function() {
        
    Route::get('/', [CustomersController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});