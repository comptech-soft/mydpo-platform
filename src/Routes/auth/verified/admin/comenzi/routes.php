<?php

use MyDpo\Http\Controllers\Admin\ComenziController;

Route::prefix('comenzi')->group( function() {
        
    Route::get('/', [ComenziController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});