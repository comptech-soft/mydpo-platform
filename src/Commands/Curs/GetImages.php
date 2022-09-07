<?php

namespace MyDpo\Commands\Curs;

use Illuminate\Console\Command;

class GetImages extends Command {

    protected $signature = 'curs:getimages';

    protected $description = 'Get images from Knolyx courses';

    public function handle() {

        $this->info('Pam pam. The command was successful!');
    
    }
}
