<?php

// use MyDpo\Http\Controllers\Validation\ValidationController;
// use MyDpo\Http\Controllers\Database\DatabaseController;
// use MyDpo\Http\Controllers\Usersession\ActivateAccountController;

// use MyDpo\Http\Controllers\System\KnolyxController;


// /**
//  * Activarea unui cont client
//  */
// Route::get('activare-cont-client/{token}', [ActivateAccountController::class, 'index'])->name('activate.account');
// Route::post('system/activare-cont-client', [ActivateAccountController::class, 'activateAccount']);
// Route::post('system/activare-cont-client/get-infos-by-token', [ActivateAccountController::class, 'getInfosByToken']);

// Route::prefix('validation')->group(function () {

//     Route::prefix('database')->group(function () {

//         Route::post('column-value-exists', [ValidationController::class, '']);
//         Route::post('', [ValidationController::class, '']);
    
//     });
    
// });

// Route::prefix('database')->group(function () {

//     Route::post('update-field', [DatabaseController::class, 'updateField']);
    
// });

// Route::put('/knolyx/webhook-process', [KnolyxController::class, 'webhookProcess']);