<?php

use MyDpo\Http\Controllers\Auth\CustomersDepartmentsController;

Route::middleware('valid-customer')->prefix('customer-departments')->group( function() {

    Route::get('/{customer_id}', [CustomersDepartmentsController::class, 'index']);

});

Route::prefix('customers-departments')->group( function() {
        
    Route::post('items', [CustomersDepartmentsController::class, 'getItems']);
    Route::post('action/{action}', [CustomersDepartmentsController::class, 'doAction']);

});

