<?php

Route::middleware(['isadmin'])->prefix('admin')->group(function () {
    
    require __DIR__ . '/clienti/routes.php';

    require __DIR__ . '/localitati/routes.php';


});