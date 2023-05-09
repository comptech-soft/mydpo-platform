<?php

Route::middleware('verified')->group(function () {

    require __DIR__ . '/customers/routes.php';

    require __DIR__ . '/centralizatoare/routes.php';

    require __DIR__ . '/customer-centralizatoare/routes.php';

});
