<?php

namespace MyDpo\Events\Authentication\Team;

use MyDpo\Events\BaseBroadcastEvent;

class CreateTeamActivation extends BaseBroadcastEvent {

    public function __construct($template, $input) {
        parent::__construct($template, $input);
    }

}