<?php

use MyDpo\Http\Controllers\Admin\ServiciiComandateController;

Route::prefix('servicii-comandate')->group( function() {
        
    Route::get('/', [ServiciiComandateController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});