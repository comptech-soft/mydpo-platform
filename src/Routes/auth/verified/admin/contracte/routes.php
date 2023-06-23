<?php

use MyDpo\Http\Controllers\Admin\ContracteController;

Route::prefix('contracte')->group( function() {
        
    Route::get('/', [ContracteController::class, 'index']);        

});