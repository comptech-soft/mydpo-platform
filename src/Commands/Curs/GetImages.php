<?php

namespace MyDpo\Commands\Curs;

use Illuminate\Console\Command;
use MyDpo\Models\Curs;

class GetImages extends Command {

    protected $signature = 'curs:getimages';

    protected $description = 'Get images from Knolyx courses';

    public function handle() {

        $courses = Curs::whereNotNull('k_id')->get();
        $this->info('Pam pam. The command was successful!' . $courses->count());
    
    }
}
