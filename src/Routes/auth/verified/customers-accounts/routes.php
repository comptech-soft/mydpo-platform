<?php

use MyDpo\Http\Controllers\Auth\CustomersAccountsController;

Route::middleware('valid-customer')->prefix('customer-accounts')->group( function() {

    Route::get('/{customer_id}', [CustomersAccountsController::class, 'index']);

});

Route::prefix('customers-accounts')->group( function() {
        
    Route::post('items', [CustomersAccountsController::class, 'getItems']);
    Route::post('action/{action}', [CustomersAccountsController::class, 'doAction']);
    Route::post('save-dashboard-permissions', [CustomersAccountsController::class, 'saveDashboardPermissions']);
    Route::post('save-folder-permissions', [CustomersAccountsController::class, 'saveFolderPermissions']);

});

Route::prefix('customers-accounts-role')->group( function() {
        
    Route::post('action/{action}', [CustomersAccountsController::class, 'updateRole']);

});
