<?php

namespace MyDpo\Traits;

trait Importable { 

    public static function doImport($input, $record) {

        return \Excel::import(self::GetImporter($input), $input['file']);
        
    }
    
}