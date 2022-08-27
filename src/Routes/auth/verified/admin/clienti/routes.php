<?php

use MyDpo\Http\Controllers\Admin\CustomersController;

Route::prefix('clienti')->group( function() {
        
    Route::get('/', [CustomersController::class, 'index']);        
    Route::post('items', [CustomersController::class, 'getItems']);
    Route::post('action/{action}', [CustomersController::class, 'doAction']);
});