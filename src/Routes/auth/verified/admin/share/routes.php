<?php

use MyDpo\Http\Controllers\Admin\ShareController;

Route::prefix('share')->group( function() {
        
    Route::get('/{entity}', 'ShareController@index'); 
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});