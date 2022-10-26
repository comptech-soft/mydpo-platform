<?php

use MyDpo\Http\Controllers\Auth\CustomersInfograficeController;


Route::middleware('valid-customer')->prefix('/customer-infografice')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}', [CustomersInfograficeController::class, 'index']);

});