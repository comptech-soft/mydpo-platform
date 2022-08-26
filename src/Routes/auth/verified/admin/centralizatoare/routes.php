<?php

use MyDpo\Http\Controllers\Admin\CentralizatoareController;

Route::prefix('centralizatoare')->group( function() {
        
    Route::get('/', [CentralizatoareController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});