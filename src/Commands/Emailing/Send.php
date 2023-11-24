<?php

namespace MyDpo\Commands\Emailing;

use Illuminate\Console\Command;
use Carbon\Carbon;

use MyDpo\Models\Customer\Emails\EmailUser;
use MyDpo\Mail\System\SystemMail;
use MyDpo\Mail\System\SuccessEmail;
use MyDpo\Mail\System\ExceptionMail;

class Send extends Command {

    protected $signature = 'emails:send';

    protected $description = 'Trimiterea de emailuri';

    public function handle() {

        $pending_emails = EmailUser::whereNull('sended_at')->orderBy('created_at')->take(5)->get();

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

            $this->send($email);
        }
        
        $this->info('Sent ' . count($pending_emails) . ' emails.');
    
    }

    protected function send($email) {
        try
        {
            // $x = 7/0;
            /**
             * Trimiteți emailul folosind clasa de email corespunzătoare
             **/ 
            \Mail::to($email->user->email)->send(
                new SystemMail(
                    user: $email->user,
                    sender: $email->sender,
                    template: $email->props['template'],
                    payload: $email->props['payload']
                ));
            
            /**
             * Se actualizeaza câmpul 'sended_at' pentru email
             */

            $email->update(['sended_at' => Carbon::now()]);

            sleep(1);

            \Mail::to(config('app.developer.email'))->send(
                new SuccessMail(
                    user: $email->user,
                    sender: $email->sender,
                    template: $email->props['template'],
                    payload: $email->props['payload']
                ));
        }
        catch(\Exception $e)
        {
            \Mail::to(config('app.developer.email'))->send(
                new ExceptionMail(
                    user: $email->user,
                    sender: $email->sender,
                    template: $email->props['template'],
                    payload: $email->props['payload']
                ));
        }
    }
}
