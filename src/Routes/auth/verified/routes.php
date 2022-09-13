<?php

use MyDpo\Http\Controllers\Usersession\MyProfileController;

/**
 * Doar pentru userii activi
 * Daca se ajunge aici ==> redirect pe http://{url}/verify-email [verification.notice]
 */
Route::middleware('verified')->group(function () {

    Route::get('my-profile', [MyProfileController::class, 'index']);

    require __DIR__ . '/customers-statuses/routes.php';
    require __DIR__ . '/customers-dashboard/routes.php';
    require __DIR__ . '/customers-dashboard-items/routes.php';
    require __DIR__ . '/customers-documents/routes.php';
    require __DIR__ . '/customers-centralizatoare/routes.php';
    require __DIR__ . '/customers-chestionare/routes.php';
    require __DIR__ . '/customers-cursuri/routes.php';
    require __DIR__ . '/customers-departments/routes.php';
    require __DIR__ . '/customers-contracte/routes.php';
    require __DIR__ . '/customers-orders/routes.php';
    require __DIR__ . '/customers-services/routes.php';
    require __DIR__ . '/customers-accounts/routes.php';
    require __DIR__ . '/cusomers-materiale/routes.php';

    require __DIR__ . '/users/routes.php';
    
    require __DIR__ . '/admin/routes.php';
    require __DIR__ . '/mydpo/routes.php';
});