<?php

namespace MyDpo\Performers\CustomerCursFile;

use MyDpo\Helpers\Perform;

class AttachFiles extends Perform {

    public function Action() {

        foreach($this->input['files'] as $i => $file)
        {
            $this->procesFile($file);
        }

    }

    private function procesFile($file) {
        
    }
}