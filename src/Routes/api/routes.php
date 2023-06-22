<?php

Route::middleware('verified')->group(function () {

    require __DIR__ . '/customer-accounts/routes.php';
    // require __DIR__ . '/customer-planuri-conformare/routes.php';
    
});
