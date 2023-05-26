<?php

use MyDpo\Http\Controllers\Admin\CountriesController;
use MyDpo\Http\Controllers\Admin\RegionsController;
use MyDpo\Http\Controllers\Admin\LocalitatiController;

Route::prefix('localitati')->group( function() {
        
    Route::get('/', [LocalitatiController::class, 'index']);        
    Route::post('items', [LocalitatiController::class, 'getItems']);
    Route::post('get-items', [LocalitatiController::class, 'getRecords']);

});

Route::prefix('countries')->group( function() {
    
    Route::post('items', [CountriesController::class, 'getItems']);
    Route::post('get-items', [CountriesController::class, 'getRecords']);

});

Route::prefix('regions')->group( function() {
    
    Route::post('items', [RegionsController::class, 'getItems']);
    Route::post('get-items', [RegionsController::class, 'getRecords']);

});