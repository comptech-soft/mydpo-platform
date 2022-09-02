<?php

use MyDpo\Http\Controllers\Auth\CustomersOrdersController;

Route::prefix('customers-orders')->group( function() {
        
    Route::post('items', [CustomersOrdersController::class, 'getItems']);
    Route::post('action/{action}', [CustomersOrdersController::class, 'doAction']);

});

