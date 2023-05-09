<?php

use MyDpo\Http\Controllers\Auth\CustomersDepartmentsController;

Route::prefix('customers-departamente')->group( function() {
            
    Route::post('get-records', [CustomersDepartmentsController::class, 'getRecords']);
    // Route::post('action/{action}', [CustomersCentralizatoareController::class, 'doAction']);

});