<?php

use MyDpo\Http\Controllers\Auth\CustomersAnalizaGapController;


Route::middleware('valid-customer')->prefix('/customer-analiza-gap')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}', [CustomersAnalizaGapController::class, 'index']);

});