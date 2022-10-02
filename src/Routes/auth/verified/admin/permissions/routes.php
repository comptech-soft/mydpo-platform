<?php

use MyDpo\Http\Controllers\Admin\PermissionsController;

Route::prefix('permissions')->group( function() {
            
    Route::post('items', [PermissionsController::class, 'getItems']);

});