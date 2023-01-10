<?php

use MyDpo\Http\Controllers\Auth\CustomersCursuriController;
use MyDpo\Http\Controllers\Auth\CustomersCursuriAccesCursController;
use MyDpo\Http\Controllers\Auth\CustomersCursuriUsersController;
use MyDpo\Http\Controllers\Auth\CustomersCursuriFilesController;
use MyDpo\Http\Controllers\Auth\CustomersCursuriParticipantsController;
use MyDpo\Http\Controllers\Admin\CursuriController;

Route::middleware('valid-customer')->prefix('customer-cursuri')->group( function() {
    Route::get('/{customer_id}/acces-curs/{customer_curs_id}', [CustomersCursuriAccesCursController::class, 'index']);
    Route::get('/{customer_id}', [CustomersCursuriController::class, 'index']);
});

Route::prefix('customers-cursuri')->group( function() {
    Route::post('items', [CustomersCursuriController::class, 'getItems']);
    Route::post('get-summary', [CustomersCursuriController::class, 'getSummary']);
});

Route::prefix('cursuri')->group( function() {
    Route::post('open-knolyx-course', [CursuriController::class, 'openKnolyxCourse']);
});

Route::prefix('customers-cursuri-users')->group( function() {        
    Route::post('items', [CustomersCursuriUsersController::class, 'getItems']);
    Route::post('counter', [CustomersCursuriUsersController::class, 'getCounter']);
    Route::post('change-status', [CustomersCursuriUsersController::class, 'changeStatus']);
    Route::post('assign-cursuri', [CustomersCursuriUsersController::class, 'assignCursuri']);
});

Route::prefix('customers-cursuri-files')->group( function() {        
    Route::post('items', [CustomersCursuriFilesController::class, 'getItems']);
    // Route::post('counter', [CustomersCursuriUsersController::class, 'getCounter']);
    // Route::post('change-status', [CustomersCursuriUsersController::class, 'changeStatus']);
    // Route::post('assign-cursuri', [CustomersCursuriUsersController::class, 'assignCursuri']);
});

Route::prefix('customers-cursuri-participants')->group( function() {        
    Route::post('items', [CustomersCursuriParticipantsController::class, 'getItems']);
    // Route::post('counter', [CustomersCursuriUsersController::class, 'getCounter']);
    // Route::post('change-status', [CustomersCursuriUsersController::class, 'changeStatus']);
    // Route::post('assign-cursuri', [CustomersCursuriUsersController::class, 'assignCursuri']);
});