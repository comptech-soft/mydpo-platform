<?php

Route::middleware('verified')->group(function () {

    require __DIR__ . '/customers/routes.php';

    require __DIR__ . '/centralizatoare/routes.php';

    require __DIR__ . '/customer-notifications/routes.php';
    require __DIR__ . '/customer-accounts/routes.php';
    require __DIR__ . '/customer-departamente/routes.php';
    require __DIR__ . '/customer-centralizatoare/routes.php';

});
