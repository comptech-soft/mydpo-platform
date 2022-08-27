<?php

Route::middleware(['isadmin'])->prefix('admin')->group(function () {
    
    require __DIR__ . '/clienti/routes.php';
    require __DIR__ . '/contracte/routes.php';
    require __DIR__ . '/comenzi/routes.php';

    require __DIR__ . '/persons/routes.php';

    require __DIR__ . '/planificare/routes.php';
    require __DIR__ . '/timesheet/routes.php';

    require __DIR__ . '/centralizatoare/routes.php';
    require __DIR__ . '/chestionare/routes.php';
    require __DIR__ . '/cursuri/routes.php';

    require __DIR__ . '/echipa/routes.php';

    require __DIR__ . '/rapoarte/routes.php';

    require __DIR__ . '/servicii/routes.php';
    require __DIR__ . '/taskuri/routes.php';
    require __DIR__ . '/registre/routes.php';

    require __DIR__ . '/sabloane-email/routes.php';
    require __DIR__ . '/sabloane-notificari/routes.php';

    require __DIR__ . '/localitati/routes.php';

    require __DIR__ . '/config/routes.php';


});