<?php

use MyDpo\Http\Controllers\Auth\CustomersRegistreController;
use MyDpo\Http\Controllers\Auth\CustomersRegistreAsiciateController;

Route::middleware('valid-customer')->prefix('/customer-registre')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}', [CustomersRegistreController::class, 'index']);

});

Route::prefix('/customers-registers-asociate')->group( function() {

    Route::post('items', [CustomersRegistreAsiciateController::class, 'getItems']);

});