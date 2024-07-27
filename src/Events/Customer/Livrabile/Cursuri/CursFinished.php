<?php

namespace MyDpo\Events\Customer\Livrabile\Cursuri;

use MyDpo\Events\BaseBroadcastEvent;

class CursFinished extends BaseBroadcastEvent {

    public function __construct($template, $input) {
        parent::__construct($template, $input);
    }
    
}
