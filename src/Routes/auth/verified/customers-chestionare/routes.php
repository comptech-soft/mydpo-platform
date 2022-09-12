<?php

use MyDpo\Http\Controllers\Auth\CustomersChestionareController;

Route::prefix('customers-chestionare')->group( function() {
        
    Route::post('items', [CustomersChestionareController::class, 'getItems']);
    Route::post('get-summary', [CustomersChestionareController::class, 'getSummary']);

});