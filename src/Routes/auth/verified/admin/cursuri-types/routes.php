<?php

use MyDpo\Http\Controllers\Admin\CursuritypesController;

Route::prefix('cursuri-types')->group( function() {
           
    Route::post('items/{type?}', [CursuritypesController::class, 'getItems']);
    
});