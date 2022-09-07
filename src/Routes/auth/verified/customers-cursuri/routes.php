<?php

use MyDpo\Http\Controllers\Auth\CustomersCursuriController;
use MyDpo\Http\Controllers\Admin\CursuriController;

Route::prefix('customers-cursuri')->group( function() {
        
    Route::post('items', [CustomersCursuriController::class, 'getItems']);
    
});



Route::prefix('cursuri')->group( function() {
        

    Route::post('open-knolyx-course', [CursuriController::class, 'openKnolyxCourse']);

    

});