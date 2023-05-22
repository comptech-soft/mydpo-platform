<?php

use MyDpo\Http\Controllers\Auth\CustomersNotificariController;

Route::prefix('customers-notifications')->group( function() {
            
    Route::post('get-records', [CustomersNotificariController::class, 'getRecords']);


});
