<?php

use MyDpo\Http\Controllers\Admin\CustomersController;

Route::prefix('clienti')->group( function() {
        
          
    Route::post('items', [CustomersController::class, 'getItems']);

    Route::post('get-customers-by-ids', [CustomersController::class, 'getCustomersByIds']);

    
});