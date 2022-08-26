<?php

use MyDpo\Http\Controllers\Admin\CountriesController;
use MyDpo\Http\Controllers\Admin\RegionsController;
use MyDpo\Http\Controllers\Admin\LocalitatiController;


Route::middleware(['isadmin'])->prefix('admin')->group(function () {

    Route::prefix('localitati')->group( function() {
        
        Route::get('/', [LocalitatiController::class, 'index']);
        
        Route::post('items', [LocalitatiController::class, 'getItems']);

    });

    

    Route::prefix('countries')->group( function() {
        
        Route::post('items', [CountriesController::class, 'getItems']);

    });

    Route::prefix('regions')->group( function() {
        
        Route::post('items', [RegionsController::class, 'getItems']);

    });
    

});