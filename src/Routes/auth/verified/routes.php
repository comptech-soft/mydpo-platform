<?php

use MyDpo\Http\Controllers\Usersession\MyProfileController;

/**
 * Doar pentru userii activi
 * Daca se ajunge aici ==> redirect pe http://{url}/verify-email [verification.notice]
 */
Route::middleware('verified')->group(function () {

    Route::get('my-profile', [MyProfileController::class, 'index']);

    require __DIR__ . '/customers-statuses/routes.php';
    require __DIR__ . '/customers-dashboard-items/routes.php';
    require __DIR__ . '/customers-documents/routes.php';

    require __DIR__ . '/admin/routes.php';

});