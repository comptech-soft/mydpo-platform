<?php

namespace MyDpo\Events\Customer\Livrabile\Centralizatorable;

use MyDpo\Events\BaseBroadcastEvent;

class InsertDocument extends BaseBroadcastEvent {

    public function __construct($template, $input) {
        parent::__construct($template, $input);
    }

}