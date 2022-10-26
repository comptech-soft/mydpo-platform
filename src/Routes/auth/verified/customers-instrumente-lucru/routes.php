<?php

use MyDpo\Http\Controllers\Auth\CustomersInstrumenteLucruController;


Route::middleware('valid-customer')->prefix('/customer-instrumente-lucru')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}', [CustomersInstrumenteLucruController::class, 'index']);

});