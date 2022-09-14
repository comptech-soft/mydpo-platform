<?php

use MyDpo\Http\Controllers\Admin\ServiciiController;

Route::prefix('servicii')->group( function() {
        
    Route::get('/', [ServiciiController::class, 'index']);        
    
    Route::post('items', [ServiciiController::class, 'getItems']);

});