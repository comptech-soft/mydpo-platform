<?php

use MyDpo\Http\Controllers\Admin\CentralizatoareController;
use MyDpo\Http\Controllers\Admin\CentralizatoareColumnsController;

Route::prefix('centralizatoare')->group( function() {
        
    // Route::get('/', [CentralizatoareController::class, 'index']);        
    
    // Route::post('items', [CentralizatoareController::class, 'getItems']);
    // Route::post('action/{action}', [CentralizatoareController::class, 'doAction']);

});

Route::prefix('centralizatoare-columns')->group( function() {
       
    // Route::post('action/{action}', [CentralizatoareColumnsController::class, 'doAction']);

});