<?php

namespace MyDpo\Performers\CustomerCentralizatorRowFile;

use MyDpo\Helpers\Perform;
// use MyDpo\Models\CustomerRegistruRowFile;

class UploadFiles extends Perform {

    public function Action() {

        if($this->files)
        {
            foreach($this->files as $i => $file)
            {
                $this->attachFile($file);
            }
        }
    }

    protected function attachFile($file) {

        dd($file);
    }
}