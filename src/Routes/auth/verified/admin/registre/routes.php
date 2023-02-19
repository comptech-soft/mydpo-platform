<?php

use MyDpo\Http\Controllers\Admin\RegistreController;
use MyDpo\Http\Controllers\Admin\RegistreColumnsController;

Route::prefix('registre')->group( function() {
        
    Route::get('/', [RegistreController::class, 'index']);        
    Route::post('items', [RegistreController::class, 'getItems']);
    Route::post('action/{action}', [RegistreController::class, 'doAction']);

});

Route::prefix('registre-columns')->group( function() {
        
    Route::post('action/{action}', [RegistreColumnsController::class, 'doAction']);

});