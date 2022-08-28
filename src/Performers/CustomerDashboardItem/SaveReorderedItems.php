<?php

namespace MyDpo\Performers\CustomerDashboardItem;

use MyDpo\Helpers\Perform;

class SaveReorderedItems extends Perform {

    public function Action() {

        dd(__METHOD__, $this->input);
    }
}