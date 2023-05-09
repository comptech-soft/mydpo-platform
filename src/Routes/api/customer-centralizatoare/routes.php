<?php

use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareController;
// use MyDpo\Http\Controllers\Admin\CentralizatoareColumnsController;

Route::prefix('customer-centralizatoare')->group( function() {
            
    Route::post('get-records', [CustomersCentralizatoareController::class, 'getRecords']);
    Route::post('action/{action}', [CustomersCentralizatoareController::class, 'doAction']);

});