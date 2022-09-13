<?php

use MyDpo\Http\Controllers\Auth\CustomersNotificariController;

Route::middleware('valid-customer')->prefix('customer-notificari')->group( function() {

    Route::get('/{customer_id}', [CustomersNotificariController::class, 'index']);

});
