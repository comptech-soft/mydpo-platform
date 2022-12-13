<?php

namespace MyDpo\Performers\CustomerCursUser;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursUser;

class GetCounter extends Perform {

    /**
     * Cate cursuri are asociate un customer
     */
    public function Action() {

        dd(__METHOD__, $this->input);
    
    }
}