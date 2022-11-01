<?php

Route::middleware(['isb2b'])->prefix('b2b')->group(function () {
    
    require __DIR__ . '/clienti/routes.php';
    require __DIR__ . '/utilizatori/routes.php';
    require __DIR__ . '/permissions/routes.php';
    

});