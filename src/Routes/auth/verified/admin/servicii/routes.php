<?php

use MyDpo\Http\Controllers\Admin\ServiciiController;
use MyDpo\Http\Controllers\Admin\ServiciiTypesController;

Route::prefix('servicii')->group( function() {
        
    Route::get('/', [ServiciiController::class, 'index']);        

    Route::post('items', [ServiciiController::class, 'getItems']);

});

Route::prefix('services-types')->group( function() {

    Route::post('items', [ServiciiTypesController::class, 'getItems']);

});