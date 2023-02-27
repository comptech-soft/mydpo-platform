<?php

use MyDpo\Http\Controllers\Auth\CustomersRegistreController;
use MyDpo\Http\Controllers\Auth\CustomersRegistreAsociateController;
use MyDpo\Http\Controllers\Auth\CustomerRegistruController;

Route::middleware('valid-customer')->prefix('/customer-registre')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}', [CustomersRegistreController::class, 'index']);

});

Route::prefix('/customers-registers-asociate')->group( function() {

    Route::post('items', [CustomersRegistreAsociateController::class, 'getItems']);
    Route::post('save-asociere', [CustomersRegistreAsociateController::class, 'saveAsociere']);
    
});

Route::middleware('valid-customer')->prefix('/customer-registru')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}/{registru_id}', [CustomerRegistruController::class, 'index']);

});

Route::prefix('/customers-registers')->group( function() {

    Route::post('items', [CustomersRegistreController::class, 'getItems']);
    Route::post('action/{action}', [CustomersRegistreController::class, 'doAction']);
    Route::post('get-next-number', [CustomersRegistreController::class, 'getNextNumber']);
    
});