<?php

use MyDpo\Http\Controllers\Auth\CustomersAccountsController;

Route::prefix('customers-persons')->group( function() {
            
    Route::post('get-records', [CustomersAccountsController::class, 'getRecords']);
    // Route::post('action/{action}', [CustomersDepartmentsController::class, 'doAction']);

});