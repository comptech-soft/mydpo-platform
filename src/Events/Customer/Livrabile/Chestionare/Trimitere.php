<?php

namespace MyDpo\Events\Customer\Livrabile\Chestionare;

use MyDpo\Events\BaseBroadcastEvent;

class Trimitere extends BaseBroadcastEvent {

    public function __construct($template, $input, $subject, $body, $url) {
        parent::__construct($template, $input, $subject, $body, $url);
    }

}