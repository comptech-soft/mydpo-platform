<?php

namespace MyDpo\Performers\Translation;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;

class Activate extends Perform {

    public function Action() {
        
        $record = \SysConfig::where('code', 'translations-activated')->first();

        dd($record);
    }

}