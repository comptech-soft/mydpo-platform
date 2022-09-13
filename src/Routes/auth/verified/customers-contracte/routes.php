<?php

use MyDpo\Http\Controllers\Auth\CustomersContracteController;

Route::middleware('valid-customer')->prefix('customer-contracte')->group( function() {

    Route::get('/{customer_id}', [CustomersContracteController::class, 'index']);

});

Route::prefix('customers-contracte')->group( function() {
        
    Route::post('items', [CustomersContracteController::class, 'getItems']);
    Route::post('action/{action}', [CustomersContracteController::class, 'doAction']);

});

