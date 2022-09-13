<?php

use MyDpo\Http\Controllers\Auth\CustomersDashboardController;

Route::prefix('customer-dashboard')->group( function() {
        
    Route::get('/{customer_id}', [CustomersDashboardController::class, 'index']);


});