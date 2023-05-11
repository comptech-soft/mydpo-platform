<?php

use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareController;
use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareRowsController;

Route::prefix('customer-centralizatoare')->group( function() {
            
    Route::post('get-records', [CustomersCentralizatoareController::class, 'getRecords']);
    Route::post('action/{action}', [CustomersCentralizatoareController::class, 'doAction']);

});

Route::prefix('customers-centralizatoare-rows')->group( function() {
            
    Route::post('get-records', [CustomersCentralizatoareRowsController::class, 'getRecords']);

    Route::post('action/setrowsstatus', [CustomersCentralizatoareRowsController::class, 'setRowsStatus']);
    Route::post('action/setrowsvisibility', [CustomersCentralizatoareRowsController::class, 'setRowsVisibility']);
   
});