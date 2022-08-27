<?php

use MyDpo\Http\Controllers\Admin\RapoarteController;

Route::prefix('rapoarte')->group( function() {
        
    Route::get('/', [RapoarteController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});