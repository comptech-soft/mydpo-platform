<?php

namespace MyDpo\Listeners\CustomerDocuments;

use MyDpo\Events\CustomerDocuments\FilesUpload as FilesUploadEvent;

class FilesUpload {

    public function handle(FilesUploadEvent $event) {
        
        dd(__METHOD__);
    }
}
