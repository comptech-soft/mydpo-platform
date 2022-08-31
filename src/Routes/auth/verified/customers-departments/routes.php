<?php

use MyDpo\Http\Controllers\Auth\CustomersDepartmentsController;

Route::prefix('customers-departments')->group( function() {
        
    Route::post('items', [CustomersDepartmentsController::class, 'getItems']);
    Route::post('action/{action}', [CustomersDepartmentsController::class, 'doAction']);

});

