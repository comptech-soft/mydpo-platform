<?php

namespace MyDpo\Performers\CustomerFile;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerFile;

class DownloadFiles extends Perform {
  
    public function Action() {

        dd(__METHOD__, $this->input);
        
    }

} 