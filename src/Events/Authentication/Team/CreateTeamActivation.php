<?php

namespace MyDpo\Events\Authentication\Team;

use MyDpo\Events\BaseBroadcastEvent;

class CreateAccountActivation extends BaseBroadcastEvent {

    public function __construct($template, $input) {
        parent::__construct($template, $input);
    }

}