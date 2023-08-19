<?php

namespace MyDpo\Commands\Notificationing;

use Illuminate\Console\Command;
use Carbon\Carbon;
use MyDpo\Models\Customer\Notifications\Notification;
use MyDpo\Events\Notifications\NotificationEvent;

class Send extends Command {

    protected $signature = 'notifications:send';

    protected $description = 'Trimiterea de notificari';

    public function handle() {

        $pending_notifications = Notification::whereStatus('created')->orderBy('created_at')->take(5)->get();

        $start_at = Carbon::now();
        $count = 0;

        foreach($pending_notifications as $notification) 
        {

            $send_at = Carbon::now();

            $minute_diff = $send_at->diffInMinutes($start_at);

            if($minute_diff >= 5)
            {
                break;
            }

            $this->info('Sendig notification #' . $notification->id);

            /**
             * Trimiteți emailul folosind clasa de email corespunzătoare
             **/ 
            event(new NotificationEvent($notification));
            
            /**
             * Actualizați câmpul 'sended_at' pentru email
             */
            // $email->update([
            //     'sended_at' => Carbon::now()
            // ]);

            sleep(1);
        }
        
        $this->info('Sent ' . count($pending_notifications) . ' notifications.');
    
    }
}
