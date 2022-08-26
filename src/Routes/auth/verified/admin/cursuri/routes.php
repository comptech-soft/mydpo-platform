<?php

use MyDpo\Http\Controllers\Admin\CursuriController;

Route::prefix('cursuri')->group( function() {
        
    Route::get('/', [CursuriController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});