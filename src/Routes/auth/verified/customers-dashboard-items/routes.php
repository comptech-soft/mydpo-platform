<?php

use MyDpo\Http\Controllers\Auth\CustomersDashboardItemsController;

Route::prefix('customers-dashboard-items')->group( function() {
        
    Route::post('items', [CustomersDashboardItemsController::class, 'getItems']);
});