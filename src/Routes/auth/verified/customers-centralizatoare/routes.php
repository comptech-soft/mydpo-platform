<?php

use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareController;

Route::prefix('customers-centralizatoare')->group( function() {
        
    Route::post('items', [CustomersCentralizatoareController::class, 'getItems']);
    Route::post('get-summary', [CustomersCentralizatoareController::class, 'getSummary']);

});