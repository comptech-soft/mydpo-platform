<?php

namespace MyDpo\Performers\CustomerFolder;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFolder;

class GetAncestors extends Perform {

    public function Action() {

        $node = CustomerFolder::find($this->input['folder_id']);
        
        $this->payload['ancestors'] = $node->ancestors;
    
    }
}