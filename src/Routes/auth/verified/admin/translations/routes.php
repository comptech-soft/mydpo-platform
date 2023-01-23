<?php

use MyDpo\Http\Controllers\Admin\TranslationController;


Route::prefix('translations')->group( function() {
        
    Route::get('/', [TranslationController::class, 'index']);        
    Route::post('items', [TranslationController::class, 'getItems']);
    Route::post('action/{action}', [TranslationController::class, 'doAction']);
   
});