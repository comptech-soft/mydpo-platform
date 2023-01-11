<?php

use MyDpo\Http\Controllers\Admin\ShareController;
use MyDpo\Http\Controllers\Admin\ShareDetailsController;

Route::prefix('share')->group( function() {
        
    Route::get('/{entity}', [ShareController::class, 'index']); 
    Route::post('items', [ShareController::class, 'getItems']);
    Route::post('action/{action}', [ShareController::class, 'doAction']);
    Route::post('get-next-number', [ShareController::class, 'getNextNumber']);

});


Route::prefix('share-materiale-details')->group( function() {
        
    Route::post('items', [ShareDetailsController::class, 'getItems']);

});