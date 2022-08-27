<?php

use MyDpo\Http\Controllers\Auth\CustomersStatusesController;

Route::prefix('customers-statuses')->group( function() {
        
    Route::post('items', [CustomersStatusesController::class, 'getItems']);
});