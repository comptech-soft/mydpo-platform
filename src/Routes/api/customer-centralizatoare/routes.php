<?php

use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareController;

Route::prefix('customer-centralizatoare')->group( function() {
            
    Route::post('get-records', [CustomersCentralizatoareController::class, 'getRecords']);
    Route::post('action/{action}', [CustomersCentralizatoareController::class, 'doAction']);

});