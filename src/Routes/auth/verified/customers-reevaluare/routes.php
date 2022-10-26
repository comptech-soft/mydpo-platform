<?php

use MyDpo\Http\Controllers\Auth\CustomersReevaluareController;


Route::middleware('valid-customer')->prefix('/customer-reevaluare')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}', [CustomersReevaluareController::class, 'index']);

});