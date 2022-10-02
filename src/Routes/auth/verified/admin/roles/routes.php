<?php

use MyDpo\Http\Controllers\Admin\RolesController;

Route::prefix('roles')->group( function() {
        
    Route::get('/', [RolesController::class, 'index']);        
    Route::post('items', [RolesController::class, 'getItems']);
    // Route::post('action/{action}', [UsersController::class, 'doAction']);
    // Route::post('update-password', [UsersController::class, 'updatePassword']);

});