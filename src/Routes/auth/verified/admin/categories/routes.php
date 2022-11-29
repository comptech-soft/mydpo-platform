<?php

use MyDpo\Http\Controllers\Admin\CategoriesController;
use MyDpo\Http\Controllers\Admin\CursuriadresareController;

Route::prefix('categories')->group( function() {
           
    Route::post('items/{type?}', [CategoriesController::class, 'getItems']);
    Route::post('action/{action}', [CategoriesController::class, 'doAction']);
    Route::post('valid-name', [CategoriesController::class, 'isValidName']);
    
});

Route::prefix('cursuri-adresare')->group( function() {
           
    Route::post('items/{type?}', [CursuriadresareController::class, 'getItems']);
    Route::post('action/{action}', [CursuriadresareController::class, 'doAction']);
    Route::post('valid-name', [CursuriadresareController::class, 'isValidName']);
    
});