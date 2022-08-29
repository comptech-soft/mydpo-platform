<?php

namespace MyDpo\Models;

class Knolyx {

    
    public static function CreateUser($user) {
        if( ! $user['k_id'] )
        {
            $kUserProvision =  \Http::withHeaders([
                'X-Project-Id' => config('knolyx.project_id'),
                'X-Api-Key' => config('knolyx.app_key')
            ])
            ->put(
                config('knolyx.endpoint') . 'user/provision',
                [
                    'firstName' => $user->first_name, 
                    'lastName' => $user->last_name,
                    'email' => $user->email,
                    'disableWelcomeEmail' => TRUE,
                ]
            )
            ->json();

            if( array_key_exists('id', $kUserProvision))
            {
                $user->k_id = $kUserProvision['id'];
                $user->save();    
            }

            $user->refresh();

            dd($user);
        }
    }
}