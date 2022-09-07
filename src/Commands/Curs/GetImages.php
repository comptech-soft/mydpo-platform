<?php

namespace MyDpo\Commands\Curs;

use Illuminate\Console\Command;
use MyDpo\Models\Curs;

class GetImages extends Command {

    protected $signature = 'curs:getimages';

    protected $description = 'Get images from Knolyx courses';

    public function handle() {

        Curs::getKnolyxCoursesImages();
        $this->info('Pam pam. The command was successful!' . $courses->count());
    
    }
}
