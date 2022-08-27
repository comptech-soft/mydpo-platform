<?php

use MyDpo\Http\Controllers\Admin\SabloaneNotificariController;

Route::prefix('sabloane-notificari')->group( function() {
        
    Route::get('/', [SabloaneNotificariController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});