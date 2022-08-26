<?php

namespace MyDpo\Listeners\User;

use Illuminate\Auth\Events\Login;

class TrackUserLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        try
        {
            activity()
                ->by($event->user)
                ->on($event->user)
                ->withProperties(
                    [
                        'input' => request()->all(),
                        'ip' => request()->all(),
                    ]
                )
                ->event('login')
                ->createdAt($now = now())
                ->log(
                    __(
                        'Utilizatorul :name a intrat in sistem', 
                        [
                            'name' => $event->user->full_name 
                        ]
                    ), 
                );

            \Auth::user()->update([
                'last_login' => $now,
            ]);
            
        }
        catch(\Exception $e)
        {

        }
    }
}
