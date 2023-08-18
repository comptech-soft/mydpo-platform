<?php

namespace MyDpo\Commands\Emailing;

use Illuminate\Console\Command;
use MyDpo\Models\Customer\Emails\EmailUser;

class Send extends Command {

    protected $signature = 'emails:send';

    protected $description = 'Trimiterea de emailuri';

    public function handle() {

        $pending_emails = EmailUser::whereNull('sended_at')
            ->take(10)
            ->get();

        dd($pending_emails);


        $this->info('Pam pam. The command was successful!');
    
    }
}
