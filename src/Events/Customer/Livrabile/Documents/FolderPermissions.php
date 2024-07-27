<?php

namespace MyDpo\Events\Customer\Livrabile\Documents;

use MyDpo\Events\BaseBroadcastEvent;

class FolderPermissions extends BaseBroadcastEvent {

    public function __construct($template, $input) {
        parent::__construct($template, $input);
    }

}