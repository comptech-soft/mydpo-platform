<?php

use MyDpo\Http\Controllers\Admin\ChestionareController;

Route::prefix('chestionare')->group( function() {
        
    Route::get('/', [ChestionareController::class, 'index']);        
   
    Route::post('items', [ChestionareController::class, 'getItems']);
    Route::post('action/{action}', [ChestionareController::class, 'doAction']);
    
});