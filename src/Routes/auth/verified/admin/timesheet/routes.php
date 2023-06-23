<?php

use MyDpo\Http\Controllers\Admin\TimesheetController;

Route::prefix('timesheet')->group( function() {
        
    Route::get('/', [TimesheetController::class, 'index']);        

});