<?php

use MyDpo\Http\Controllers\Auth\CustomersOrdersController;

Route::middleware('valid-customer')->prefix('customer-comenzi')->group( function() {

    Route::get('/{customer_id}', [CustomersOrdersController::class, 'index']);

});

Route::prefix('customers-orders')->group( function() {
        
    Route::post('items', [CustomersOrdersController::class, 'getItems']);
    Route::post('action/{action}', [CustomersOrdersController::class, 'doAction']);

});

