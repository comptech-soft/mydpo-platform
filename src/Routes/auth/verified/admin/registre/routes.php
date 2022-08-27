<?php

use MyDpo\Http\Controllers\Admin\RegistreController;

Route::prefix('registre')->group( function() {
        
    Route::get('/', [RegistreController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});