<?php

use MyDpo\Http\Controllers\Auth\CustomersChestionareController;

Route::prefix('customers-centralizatoare')->group( function() {
        
    Route::post('items', [CustomersChestionareController::class, 'getItems']);
    Route::post('get-summary', [CustomersChestionareController::class, 'getSummary']);

});