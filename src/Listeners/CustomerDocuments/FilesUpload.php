<?php

namespace MyDpo\Listeners\CustomerDocuments;

use MyDpo\Events\CustomerDocuments\FilesUpload as FilesUploadEvent;

class FilesUpload {

    public function handle(FilesUploadEvent $event) {

        \Log::info(__METHOD__ . '--> Files Uploaded Listener');

    }
}
