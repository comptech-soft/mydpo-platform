<?php

use MyDpo\Http\Controllers\Admin\ChestionareController;

Route::prefix('chestionare')->group( function() {
        
    Route::get('/', [ChestionareController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});