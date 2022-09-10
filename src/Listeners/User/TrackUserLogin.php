<?php

namespace MyDpo\Listeners\User;

use Illuminate\Auth\Events\Login;

class TrackUserLogin {
    /**
     * Handle the event.
     */
    public function handle(Login $event) {
        try
        {
            activity()
                ->by($event->user)
                ->on($event->user)
                ->withProperties(
                    [
                        'input' => request()->all(),
                        'ip' => request()->ip(),
                        'user' => $event->user,
                    ]
                )
                ->event('change-password')
                ->createdAt($now = now())
                ->log(
                    __(
                        ':name a achimbat parola', 
                        [
                            'name' => $event->user->full_name 
                        ]
                    ), 
                );
        }
        catch(\Exception $e)
        {

        }
    }
}
