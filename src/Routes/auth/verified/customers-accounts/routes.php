<?php

// use MyDpo\Http\Controllers\Auth\CustomersAccountsController;

// Route::middleware('valid-customer')->prefix('customer-accounts')->group( function() {

//     Route::get('/{customer_id}', [CustomersAccountsController::class, 'index']);

// });

// Route::prefix('customers-accounts')->group( function() {
        
//     Route::post('items', [CustomersAccountsController::class, 'getItems']);
//     Route::post('action/{action}', [CustomersAccountsController::class, 'doAction']);
//     Route::post('save-dashboard-permissions', [CustomersAccountsController::class, 'saveDashboardPermissions']);
//     Route::post('save-folder-permissions', [CustomersAccountsController::class, 'saveFolderPermissions']);
//     Route::post('save-permissions', [CustomersAccountsController::class, 'savePermissions']);
//     Route::post('save-folder-access', [CustomersAccountsController::class, 'saveFolderAccess']);
//     Route::post('assign-user', [CustomersAccountsController::class, 'assignUser']);

// });

// Route::prefix('customers-accounts-role')->group( function() {
        
//     Route::post('action/{action}', [CustomersAccountsController::class, 'updateRole']);

// });

// Route::prefix('customers-accounts-status')->group( function() {
        
//     Route::post('action/{action}', [CustomersAccountsController::class, 'updateStatus']);

// });
