<?php

namespace MyDpo\Commands\Emailing;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\RateLimiter; 

use MyDpo\Models\Customer\Emails\EmailUser;

class Send extends Command {

    protected $signature = 'emails:send';

    protected $description = 'Trimiterea de emailuri';

    public function handle() {

        $pending_emails = EmailUser::whereNull('sended_at')
            ->take(10)
            ->get();

        foreach($pending_emails as $email) 
        {
            // Introduceți o pauză de 6 secunde (60 secunde / 10 emailuri)
            RateLimiter::throttle('email-sending')->allow(1)->every(6);
    
            // // Trimiteți emailul folosind clasa de email corespunzătoare
            // \Mail::to($email->email_address)->send(new YourMailClass($email));
            
            // // Actualizați câmpul 'sended_at' pentru email
            // $email->update(['sended_at' => now()]);
        }
        
        $this->info('Sent ' . count($pending_emails) . ' emails.');
    
    }
}
