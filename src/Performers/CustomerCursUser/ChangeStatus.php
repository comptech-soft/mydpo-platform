<?php

namespace MyDpo\Performers\CustomerCursUser;

use MyDpo\Helpers\Perform;


class ChangeStatus extends Perform {

    /**
     * Cate cursuri are asociate un customer
     */
    public function Action() {

        dd(__METHOD__, $this->input);
    
    }
}