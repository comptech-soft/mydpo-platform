<?php

use MyDpo\Http\Controllers\Auth\CustomersDocumentsController;
use MyDpo\Http\Controllers\Auth\CustomersFoldersController;
use MyDpo\Http\Controllers\Auth\CustomersFilesController;
use MyDpo\Http\Controllers\Auth\CustomersFoldersPermssionsController;

Route::middleware('valid-customer')->prefix('customer-documents')->group( function() {

    Route::get('download/{customer_id}/{file_id}', [CustomersDocumentsController::class, 'downloadFile']);
    Route::get('/{customer_id}', [CustomersDocumentsController::class, 'index']);

});

Route::prefix('customers-folders')->group( function() {
        
    Route::post('items', [CustomersFoldersController::class, 'getItems']);
    Route::post('all-items', [CustomersFoldersController::class, 'getAllItems']);
    Route::post('action/{action}', [CustomersFoldersController::class, 'doAction']);
    Route::post('get-ancestors', [CustomersFoldersController::class, 'getAncestors']);
    Route::post('get-summary', [CustomersFoldersController::class, 'getSummary']);
    Route::post('save-orderd-folders', [CustomersFoldersController::class, 'saveOrderdFolders']);
    Route::post('save-folders-access', [CustomersFoldersController::class, 'saveFoldersAccess']);

});

Route::prefix('customers-files')->group( function() {
        
    Route::post('items', [CustomersFilesController::class, 'getItems']);
    Route::post('action/{action}', [CustomersFilesController::class, 'doAction']);
    Route::post('change-files-status', [CustomersFilesController::class, 'changeFilesStatus']);
    Route::post('move-files', [CustomersFilesController::class, 'moveFiles']);
    Route::post('delete-files', [CustomersFilesController::class, 'deleteFiles']);
    Route::post('download-files', [CustomersFilesController::class, 'downloadFiles']);

});

Route::prefix('customers-folders-permissions')->group( function() {
    
    Route::post('items', [CustomersFoldersPermssionsController::class, 'getItems']);

});
