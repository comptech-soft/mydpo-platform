<?php

use MyDpo\Http\Controllers\Admin\CentralizatoareController;
use MyDpo\Http\Controllers\Admin\CentralizatoareColumnsController;

Route::prefix('centralizatoare')->group( function() {
            
    Route::post('get-records', [CentralizatoareController::class, 'getRecords']);

    /**
     * Returneaza toate centralizatoarele din sistem
     * Cu customers-centralizatoare-asociere.is_associated 
     * Pentru un customer_id
     */
    Route::post('get-customer-asociere', [CentralizatoareController::class, 'getCustomerAsociere']);

    /**
     * Salveaza asocierile customer <--> centralizatoare in tabela customers-centralizatoare-asociere
     */
    Route::post('save-customer-asociere', [CentralizatoareController::class, 'saveCustomerAsociere']);
    
    

});

Route::prefix('centralizatoare-columns')->group( function() {

    Route::post('get-records', [CentralizatoareColumnsController::class, 'getRecords']);

});