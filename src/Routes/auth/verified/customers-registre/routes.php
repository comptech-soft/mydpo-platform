<?php

use MyDpo\Http\Controllers\Admin\RegistreController;
use MyDpo\Http\Controllers\Admin\RegistreColumnsController;
use MyDpo\Http\Controllers\Auth\CustomersRegistreController;
use MyDpo\Http\Controllers\Auth\CustomersRegistreAsociateController;
use MyDpo\Http\Controllers\Auth\CustomerRegistruController;
use MyDpo\Http\Controllers\Auth\CustomersRegistreRowsController;
use MyDpo\Http\Controllers\Auth\CustomersRegistreUsersController;

Route::prefix('registre')->group( function() {
        
    Route::get('/', [RegistreController::class, 'index']);        
    Route::post('items', [RegistreController::class, 'getItems']);
    Route::post('action/{action}', [RegistreController::class, 'doAction']);

});

Route::prefix('registre-columns')->group( function() {
        
    Route::post('action/{action}', [RegistreColumnsController::class, 'doAction']);
    Route::post('reorder-columns', [RegistreColumnsController::class, 'reorderColumns']);

});


Route::middleware('valid-customer')->prefix('/customer-registre')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}', [CustomersRegistreController::class, 'index']);

});

Route::prefix('/customers-registers-asociate')->group( function() {

    Route::post('items', [CustomersRegistreAsociateController::class, 'getItems']);
    Route::post('save-asociere', [CustomersRegistreAsociateController::class, 'saveAsociere']);
    
});

Route::middleware('valid-customer')->prefix('/customer-registru')->group( function() {

    Route::middleware('is-activated')->get('/{customer_id}/{registru_id}', [CustomerRegistruController::class, 'index']);

});

Route::prefix('/customers-registers')->group( function() {

    Route::post('items', [CustomersRegistreController::class, 'getItems']);
    Route::post('action/{action}', [CustomersRegistreController::class, 'doAction']);
    Route::post('get-next-number', [CustomersRegistreController::class, 'getNextNumber']);
    Route::post('register-download', [CustomersRegistreController::class, 'registerDownload']);
    Route::get('register-download-preview/{id}', [CustomersRegistreController::class, 'registerDownloadPreview']);
    Route::post('register-upload', [CustomersRegistreController::class, 'registerUpload']);
    Route::post('register-save-access', [CustomersRegistreController::class, 'registerSaveAccess']);
    
});

Route::prefix('/customers-registers-rows')->group( function() {
    Route::post('action/{action}', [CustomersRegistreRowsController::class, 'doAction']);
    Route::post('change-status', [CustomersRegistreRowsController::class, 'changeStatus']);  
    Route::post('change-stare', [CustomersRegistreRowsController::class, 'changeStare']);    
    Route::post('delete-rows', [CustomersRegistreRowsController::class, 'deleteRows']);    
});

Route::prefix('/customers-registers-users')->group( function() {
    Route::post('items', [CustomersRegistreUsersController::class, 'getItems']);    
});