<?php

namespace MyDpo\Performers\Translation;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;

class Activate extends Perform {

    public function Action() {
        
        $record = SysConfig::where('code', 'translations-activated')->first();

        if($record->value == '1')
        {
            $record->value == '0';
        }
        else
        {
            $record->value == '1';
        }

        $this->payload = $record;
    }

}