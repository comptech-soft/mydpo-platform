<?php

use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareController;

Route::middleware('valid-customer')->prefix('/customer-centralizatoare')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}', [CustomersAnalizaGapController::class, 'index']);

});

Route::prefix('customers-centralizatoare')->group( function() {

    Route::post('items', [CustomersCentralizatoareController::class, 'getItems']);
    Route::post('get-summary', [CustomersCentralizatoareController::class, 'getSummary']);

});