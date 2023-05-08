<?php

use MyDpo\Http\Controllers\Admin\CustomersController;

Route::prefix('customers')->group( function() {
            
    Route::post('get-records', [CustomersController::class, 'getRecords']);

});