<?php

use MyDpo\Http\Controllers\Auth\CustomersServicesController;

Route::prefix('customers-services')->group( function() {
        
    Route::post('items', [CustomersServicesController::class, 'getItems']);
    Route::post('action/{action}', [CustomersServicesController::class, 'doAction']);

});

