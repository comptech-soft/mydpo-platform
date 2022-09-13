<?php

use MyDpo\Http\Controllers\Auth\CustomersCursuriController;
use MyDpo\Http\Controllers\Admin\CursuriController;


Route::middleware('valid-customer')->prefix('customer-cursuri')->group( function() {

    Route::get('/{customer_id}', [CustomersCursuriController::class, 'index']);

});

Route::prefix('customers-cursuri')->group( function() {
        
    Route::post('items', [CustomersCursuriController::class, 'getItems']);
    Route::post('get-summary', [CustomersCursuriController::class, 'getSummary']);

});



Route::prefix('cursuri')->group( function() {
        

    Route::post('open-knolyx-course', [CursuriController::class, 'openKnolyxCourse']);

    

});