<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\CustomerRegistruAsociat;

class ExportRegister extends Perform {

    public function Action() {

        dd(__METHOD__, $this->input);
    
    }
}