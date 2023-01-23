<?php

use MyDpo\Http\Controllers\Admin\TranslationsController;


Route::prefix('translations')->group( function() {
        
    Route::get('/', [TranslationsController::class, 'index']);        
    Route::post('items', [TranslationsController::class, 'getItems']);
    Route::post('action/{action}', [TranslationsController::class, 'doAction']);
    Route::post('create-file', [TranslationsController::class, 'createFile']);
   
});