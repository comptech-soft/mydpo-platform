<?php

namespace MyDpo\Performers\Customer\Account;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\Customer\Registre\RowFile;

class GetUsers extends Perform {

    public function Action() {

       
        $this->payload = [
            'record' => __METHOD__,
        ];
    }
       
}