<?php

use MyDpo\Http\Controllers\Admin\CategoriesController;

Route::prefix('categories')->group( function() {
           
    Route::post('items/{type?}', [CategoriesController::class, 'getItems']);
    
});