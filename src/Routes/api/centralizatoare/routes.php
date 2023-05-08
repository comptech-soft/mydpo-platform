<?php

use MyDpo\Http\Controllers\Admin\CentralizatoareController;

Route::prefix('centralizatoare')->group( function() {
            
    Route::post('get-records', [CentralizatoareController::class, 'getRecords']);

});