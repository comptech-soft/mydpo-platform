<?php

use MyDpo\Http\Controllers\Admin\PersonsController;

Route::prefix('persons')->group( function() {
        
    Route::get('/', [PersonsController::class, 'index']);   

});