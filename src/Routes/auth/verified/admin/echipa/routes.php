<?php

use MyDpo\Http\Controllers\Admin\TeamController;

$prefix = (config('app.platform') == 'admin') ? 'team' : 'my-team';

Route::prefix($prefix)->group( function() {
        
    Route::get('/', [TeamController::class, 'index']);        

});