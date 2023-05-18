<?php

use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareController;
use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareRowsController;
use MyDpo\Http\Controllers\Auth\CustomersCentralizatoareRowsFilesController;

Route::prefix('customer-centralizatoare')->group( function() {
            
    Route::post('get-records', [CustomersCentralizatoareController::class, 'getRecords']);

    Route::post('get-next-number', [CustomersCentralizatoareController::class, 'getNextNumber']);

    Route::post('action/export', [CustomersCentralizatoareController::class, 'doExport']);
    Route::post('action/import', [CustomersCentralizatoareController::class, 'doImport']);
    
    Route::post('action/{action}', [CustomersCentralizatoareController::class, 'doAction']);

});

Route::prefix('customers-centralizatoare-rows')->group( function() {
            
    Route::post('get-records', [CustomersCentralizatoareRowsController::class, 'getRecords']);

    Route::post('action/insert', [CustomersCentralizatoareRowsController::class, 'insertRow']);
    Route::post('action/update', [CustomersCentralizatoareRowsController::class, 'updateRow']);
    Route::post('action/delete', [CustomersCentralizatoareRowsController::class, 'deleteRow']);
    Route::post('action/setrowsstatus', [CustomersCentralizatoareRowsController::class, 'setRowsStatus']);
    Route::post('action/setrowsvisibility', [CustomersCentralizatoareRowsController::class, 'setRowsVisibility']);
    Route::post('action/deleterows', [CustomersCentralizatoareRowsController::class, 'deleteRows']);
   
});

Route::prefix('customers-centralizatoare-rows-files')->group( function() {
            
    Route::post('get-records', [CustomersCentralizatoareRowsFilesController::class, 'getRecords']);
    Route::post('action/insert', [CustomersCentralizatoareRowsFilesController::class, 'uploadFiles']);
   
   
});