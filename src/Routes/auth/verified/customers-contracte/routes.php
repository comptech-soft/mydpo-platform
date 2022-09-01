<?php

use MyDpo\Http\Controllers\Auth\CustomersContracteController;

Route::prefix('customers-contracte')->group( function() {
        
    Route::post('items', [CustomersContracteController::class, 'getItems']);
    Route::post('action/{action}', [CustomersContracteController::class, 'doAction']);

});

