<?php

use MyDpo\Http\Controllers\System\WelcomeController;
use MyDpo\Http\Controllers\System\ConfigController;
use MyDpo\Http\Controllers\Validation\ValidationController;

use MyDpo\Http\Controllers\System\ContactController;
use MyDpo\Http\Controllers\System\TermeniController;
use MyDpo\Http\Controllers\System\NotaController;

Route::get('/', [WelcomeController::class, 'index']);

Route::post('system/get-config', [ConfigController::class, 'getConfig']);

Route::prefix('validation')->group(function () {

    Route::prefix('database')->group(function () {

        Route::post('column-value-exists', [ValidationController::class, 'columnValueExists']);
        Route::post('column-value-unique', [ValidationController::class, 'columnValueUnique']);
    
    });
    
});


Route::get('/contact', [ContactController::class, 'index']);
Route::get('/termeni-si-conditii', [TermeniController::class, 'index']);
Route::get('/nota-informare', [NotaController::class, 'index']);