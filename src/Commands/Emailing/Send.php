<?php

namespace MyDpo\Commands\Emailing;

use Illuminate\Console\Command;
use Carbon\Carbon;

use MyDpo\Models\Customer\Emails\EmailUser;

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

            $this->sendEmail($email);
        }
        
        $this->info('Sent ' . count($pending_emails) . ' emails.');
    
    }

    protected function sendEmail($email) {
        try
        {
            /**
             * Trimiteți emailul folosind clasa de email corespunzătoare
             **/ 
            \Mail::to($email->user->email)->send(
                new \MyDpo\Mail\System\SystemMail(
                    user: $email->user,
                    sender: $email->sender,
                    template: $email->props['template'],
                    payload: $email->props['payload']
                ));
            
            /**
             * Se actualizeaza câmpul 'sended_at' pentru email
             */
            $email->update(['sended_at' => Carbon::now()]);
            
            \Mail::to(config('app.developer.email'))->send(new \MyDpo\Mail\System\SuccessMail(
                user: $email->user,
                sender: $email->sender,
                template: $email->props['template'],
                payload: $email->props['payload']
            ));

            sleep(1);
        }
        catch(\Exception $e)
        {
            \Mail::to(config('app.developer.email'))->send(
                new \MyDpo\Mail\System\ExceptionMail(
                    user: $email->user,
                    sender: $email->sender,
                    template: $email->props['template'],
                    payload: [
                        ...$email->props['payload'],
                        'file' => $e->getFile(),
                        'line' => $e->getLine(),
                        'message' => $e->getMessage(),
                    ],
                ));
        }
    }
}
