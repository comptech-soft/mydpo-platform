<?php

use MyDpo\Http\Controllers\Auth\CustomersAccountsController;

Route::prefix('customers-accounts')->group( function() {
        
    Route::post('items', [CustomersAccountsController::class, 'getItems']);
    Route::post('action/{action}', [CustomersAccountsController::class, 'doAction']);

});

