<?php

use MyDpo\Http\Controllers\Admin\RegistreController;

Route::prefix('registre')->group( function() {
        
    Route::get('/', [RegistreController::class, 'index']);        
    Route::post('items', [RegistreController::class, 'getItems']);
    Route::post('action/{action}', [RegistreController::class, 'doAction']);

});