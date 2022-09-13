<?php

use MyDpo\Http\Controllers\Auth\CustomersEmailsController;

Route::middleware('valid-customer')->prefix('customer-emails')->group( function() {

    Route::get('/{customer_id}', [CustomersEmailsController::class, 'index']);

});
