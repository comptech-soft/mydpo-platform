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

    /**
     * CUSTOMER DASHBOARD ITEMS
     */
    require __DIR__ . '/customers-analiza-gap/routes.php';
    require __DIR__ . '/customers-plan-conformare/routes.php';
    require __DIR__ . '/customers-reevaluare/routes.php';
    require __DIR__ . '/customers-documents/routes.php';
    require __DIR__ . '/customers-registre/routes.php';    
    require __DIR__ . '/customers-centralizatoare/routes.php';
    require __DIR__ . '/customers-chestionare/routes.php';
    require __DIR__ . '/customers-studii-caz/routes.php';
    require __DIR__ . '/customers-infografice/routes.php';
    require __DIR__ . '/customers-sfaturi-dpo/routes.php';
    require __DIR__ . '/customers-dpia-tool/routes.php';
    require __DIR__ . '/customers-instrumente-lucru/routes.php';

    require __DIR__ . '/customers-cursuri/routes.php';
    require __DIR__ . '/customers-departments/routes.php';
    require __DIR__ . '/customers-contracte/routes.php';
    require __DIR__ . '/customers-orders/routes.php';
    require __DIR__ . '/customers-services/routes.php';
    require __DIR__ . '/customers-accounts/routes.php';
    require __DIR__ . '/customers-materiale/routes.php';
    require __DIR__ . '/customers-team/routes.php';
    require __DIR__ . '/customers-rapoarte-lunare/routes.php';
    require __DIR__ . '/customers-taskuri/routes.php';
    require __DIR__ . '/customers-emails/routes.php';
    require __DIR__ . '/customers-notificari/routes.php';

    require __DIR__ . '/clienti/routes.php';
    require __DIR__ . '/users/routes.php';
    require __DIR__ . '/users-statuses/routes.php';
    require __DIR__ . '/users-customers/routes.php';

    require __DIR__ . '/admin/routes.php';
    require __DIR__ . '/mydpo/routes.php';
});