<?php

namespace MyDpo\Performers\Translation;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Translation;

class CreateFile extends Perform {

    public function Action() {
        
        $file = str_replace('\\', '/', resource_path()) . '/comptech-soft/translations/en.js';

        $str = "module.exports = {" . chr(13) . chr(10);
        foreach(Translation::all() as $i => $record) {

            if(!! $record->en)
            {
                $str .= ('"' . $record->ro . '": "' . $record->en . '"' . chr(13) . chr(10));
            }
        }
        $str .= chr(13) . chr(10). "}";

        \File::put(($file), $str);
    }

}