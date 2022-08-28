<?php

use MyDpo\Http\Controllers\Auth\CustomersFoldersController;

Route::prefix('customers-folders')->group( function() {
        
    Route::post('items', [CustomersFoldersController::class, 'getItems']);
});