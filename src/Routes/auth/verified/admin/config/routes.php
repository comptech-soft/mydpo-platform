<?php

use MyDpo\Http\Controllers\Admin\ConfigController;

Route::prefix('config')->group( function() {
        
    Route::get('/', [ConfigController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});