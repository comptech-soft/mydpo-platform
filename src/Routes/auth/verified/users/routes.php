<?php

// use MyDpo\Http\Controllers\Auth\UsersController;
// use MyDpo\Http\Controllers\Admin\UserDashboardController;
// use MyDpo\Http\Controllers\Admin\RolesController;
// use MyDpo\Http\Controllers\Admin\UtilizatoriPlatformaController;
// use MyDpo\Http\Controllers\Admin\UsersController as UtilizatoriController;

// Route::prefix('users')->group( function() {
        
//     Route::post('items', [UsersController::class, 'getItems']);
//     Route::post('action/{action}', [UsersController::class, 'doAction']);
//     Route::post('change-password', [UsersController::class, 'changePassword']);
//     Route::post('save-user-settings', [UsersController::class, 'saveUserSettings']);
    
// });

// Route::prefix('utilizatori')->group( function() {
        
//     Route::post('status/update', [UtilizatoriController::class, 'updateStatus']);
    
// });

// Route::prefix('roles')->group( function() {
        
//     Route::post('items', [RolesController::class, 'getItems']);
//     // Route::post('action/{action}', [UsersController::class, 'doAction']);
// });

// Route::prefix('utilizator-dashboard')->group( function() {
        
//     Route::get('/{source}/{user_id}/{customer_id?}', [UserDashboardController::class, 'index']);        
   
// });

// Route::middleware(['isadmin'])->prefix('utilizatori-platforma')->group( function() {
//     Route::get('/', [UtilizatoriPlatformaController::class, 'index']);   
// });

