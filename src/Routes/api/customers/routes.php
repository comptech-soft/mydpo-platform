<?php

use MyDpo\Http\Controllers\Admin\CustomersController;

Route::prefix('customers')->group( function() {
            
    Route::post('items', [CustomersController::class, 'getItems']);

});