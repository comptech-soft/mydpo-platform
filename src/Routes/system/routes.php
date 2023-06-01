<?php

Route::middleware('verified')->prefix('system')->group(function () {

    require __DIR__ . '/routes/routes.php';
    
});
