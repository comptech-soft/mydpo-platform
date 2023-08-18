<?php

namespace MyDpo\Commands\Emailing;

use Illuminate\Console\Command;

class Send extends Command {

    protected $signature = 'emails:send';

    protected $description = 'Trimiterea de emailuri';

    public function handle() {

        $this->info('Pam pam. The command was successful!');
    
    }
}
