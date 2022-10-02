<?php

use MyDpo\Http\Controllers\Admin\PermissionsController;

Route::prefix('permissions')->group( function() {

    Route::get('/', [PermissionsController::class, 'index']);       
    Route::post('items', [PermissionsController::class, 'getItems']);

});