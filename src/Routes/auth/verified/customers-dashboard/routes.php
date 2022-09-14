<?php

use MyDpo\Http\Controllers\Auth\CustomersDashboardController;

Route::middleware(['valid-customer', 'isadmin'])->prefix('customer-dashboard')->group( function() {
        
    Route::get('/{customer_id}', [CustomersDashboardController::class, 'index']);


});