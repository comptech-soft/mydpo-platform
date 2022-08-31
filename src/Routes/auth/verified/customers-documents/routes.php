<?php

use MyDpo\Http\Controllers\Auth\CustomersFoldersController;
use MyDpo\Http\Controllers\Auth\CustomersFilesController;

Route::prefix('customers-folders')->group( function() {
        
    Route::post('items', [CustomersFoldersController::class, 'getItems']);
    Route::post('action/{action}', [CustomersFoldersController::class, 'doAction']);

});

Route::prefix('customers-files')->group( function() {
        
    Route::post('action/{action}', [CustomersFilesController::class, 'doAction']);
    Route::post('change-files-status', [CustomersFilesController::class, 'changeFilesStatus']);
    Route::post('delete-files', [CustomersFilesController::class, 'deleteFiles']);

});