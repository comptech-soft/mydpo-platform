<?php

use MyDpo\Http\Controllers\Auth\CustomersCursuriController;

Route::prefix('customers-cursuri')->group( function() {
        
    Route::post('items', [CustomersCursuriController::class, 'getItems']);
    
});
