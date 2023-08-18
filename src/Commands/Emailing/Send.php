<?php

namespace MyDpo\Commands\Emailing;

use Illuminate\Console\Command;
use Carbon\Carbon;

use MyDpo\Models\Customer\Emails\EmailUser;
use MyDpo\Mail\System\SystemMail;

class Send extends Command {

    protected $signature = 'emails:send';

    protected $description = 'Trimiterea de emailuri';

    public function handle() {

        $pending_emails = EmailUser::whereNull('sended_at')
            ->take(5)
            ->get();

        $start_at = Carbon::now();
        $count = 0;

        foreach($pending_emails as $email) 
        {

            $send_at = Carbon::now();

            $minute_diff = $send_at->diffInMinutes($start_at);

            if($minute_diff >= 5)
            {
                break;
            }

            /**
             * Trimiteți emailul folosind clasa de email corespunzătoare
             **/ 
            \Mail::to($email->user->email)->send(
                new SystemMail(
                    user: $email->user,
                    sender: $email->sender,
                    template: $email->props['template'],
                ));
            
            /**
             * Actualizați câmpul 'sended_at' pentru email
             */
            $email->update([
                'sended_at' => Carbon::now()
            ]);

            sleep(1);
        }
        
        $this->info('Sent ' . count($pending_emails) . ' emails.');
    
    }
}