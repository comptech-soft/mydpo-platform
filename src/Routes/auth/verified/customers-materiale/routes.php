<?php

use MyDpo\Http\Controllers\Auth\CustomersShareMaterialeController;

Route::prefix('customers-share-materiale')->group( function() {
        
    /**
     * Aduce din tabele 'share-materiale' materialele unui client
     */
    Route::post('items', [CustomersShareMaterialeController::class, 'getItems']);
    // Route::post('action/{action}', [CustomersFoldersController::class, 'doAction']);
    // Route::post('get-ancestors', [CustomersFoldersController::class, 'getAncestors']);
    // Route::post('get-summary', [CustomersFoldersController::class, 'getSummary']);
});
