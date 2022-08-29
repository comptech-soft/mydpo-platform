<?php

namespace MyDpo\Performers\Curs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;

class OpenKnolyxCourse extends Perform {

    public function Action() {

        dd(__METHOD__, $this->input);
    }

}