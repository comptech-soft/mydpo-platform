<?php

use MyDpo\Http\Controllers\System\WelcomeController;
use MyDpo\Http\Controllers\System\ConfigController;
use MyDpo\Http\Controllers\Validation\ValidationController;
use MyDpo\Http\Controllers\Database\DatabaseController;
use MyDpo\Http\Controllers\Usersession\ActivateAccountController;

use MyDpo\Http\Controllers\System\ContactController;
use MyDpo\Http\Controllers\System\TermeniController;
use MyDpo\Http\Controllers\System\NotaController;
use MyDpo\Http\Controllers\System\TranslationsController;
use MyDpo\Http\Controllers\System\UploadsController;
use MyDpo\Http\Controllers\System\KnolyxController;

Route::get('/', [WelcomeController::class, 'index']);

/**
 * Activarea unui cont client
 */
Route::get('activare-cont-client/{token}', [ActivateAccountController::class, 'index'])->name('activate.account');
Route::post('system/activare-cont-client', [ActivateAccountController::class, 'activateAccount']);
Route::post('system/activare-cont-client/get-infos-by-token', [ActivateAccountController::class, 'getInfosByToken']);

Route::prefix('system')->group(function () {
    Route::get('set-locale/{locale}', [ConfigController::class, 'setLocale']);

    Route::post('get-file-properties', [UploadsController::class, 'getFileProperties']);
});

Route::prefix('validation')->group(function () {

    Route::prefix('database')->group(function () {

        Route::post('column-value-exists', [ValidationController::class, 'columnValueExists']);
        Route::post('column-value-unique', [ValidationController::class, 'columnValueUnique']);
    
    });
    
});

Route::prefix('database')->group(function () {

    Route::post('update-field', [DatabaseController::class, 'updateField']);
    
});

Route::prefix('translations')->group(function () {

    Route::post('create-key', [TranslationsController::class, 'createKey']);
    
});


Route::get('/contact', [ContactController::class, 'index']);
Route::get('/termeni-si-conditii', [TermeniController::class, 'index']);
Route::get('/nota-informare', [NotaController::class, 'index']);

Route::put('/knolyx/webhook-process', [KnolyxController::class, 'webhookProcess']);