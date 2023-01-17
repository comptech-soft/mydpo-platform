<?php

namespace MyDpo\Performers\CustomerCurs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursUser;

class StergereParticipant extends Perform {

    public function Action() {

        dd(__METHOD__, $this->input);
    
    }
}