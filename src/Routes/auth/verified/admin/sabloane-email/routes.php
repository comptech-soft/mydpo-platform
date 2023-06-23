<?php

use MyDpo\Http\Controllers\Admin\SabloaneEmailController;

Route::prefix('sabloane-email')->group( function() {
        
    Route::get('/', [SabloaneEmailController::class, 'index']);        

});