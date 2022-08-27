<?php

use MyDpo\Http\Controllers\Admin\TaskuriController;

Route::prefix('taskuri')->group( function() {
        
    Route::get('/', [TaskuriController::class, 'index']);        
    // Route::post('items', [LocalitatiController::class, 'getItems']);

});