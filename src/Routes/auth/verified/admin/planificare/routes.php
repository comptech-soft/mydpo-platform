<?php

use MyDpo\Http\Controllers\Admin\PlanificareController;

Route::prefix('planificare')->group( function() {
        
    Route::get('/', [PlanificareController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});