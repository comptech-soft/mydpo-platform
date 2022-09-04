<?php

use MyDpo\Http\Controllers\Auth\CustomersShareMaterialeDetailsController;

Route::prefix('customers-share-materiale-details')->group( function() {
        
    Route::post('items', [CustomersShareMaterialeDetailsController::class, 'getItems']);
    // Route::post('action/{action}', [CustomersFoldersController::class, 'doAction']);
    // Route::post('get-ancestors', [CustomersFoldersController::class, 'getAncestors']);
    // Route::post('get-summary', [CustomersFoldersController::class, 'getSummary']);
});
