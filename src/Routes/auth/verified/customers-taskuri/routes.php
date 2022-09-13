<?php

use MyDpo\Http\Controllers\Auth\CustomersTaskuriController;

Route::middleware('valid-customer')->prefix('customer-taskuri')->group( function() {

    Route::get('/{customer_id}', [CustomersTaskuriController::class, 'index']);

});
