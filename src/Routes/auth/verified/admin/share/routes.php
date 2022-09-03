<?php

use MyDpo\Http\Controllers\Admin\ShareController;

Route::prefix('share')->group( function() {
        
    Route::get('/{entity}', [ShareController::class, 'index']); 
    Route::post('items', [ShareController::class, 'getItems']);

});