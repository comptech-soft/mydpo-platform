<?php

namespace MyDpo\Models;

class Knolyx {

    /**
     * #1. PUT {{baseURL}}/public/api/v1/user/provision
     */
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
        }

        return $user;
    }

    /**
     * #2. GET {{baseURL}}/public/api/v1/business-rule/course/{{courseId}}
     */
    public static function GetCourseRole($course) {
        $response =  \Http::withHeaders([
            'X-Project-Id' => config('knolyx.project_id'),
            'X-Api-Key' => config('knolyx.app_key')
        ])
        ->get(config('knolyx.endpoint') . 'business-rule/course/' . $course->k_id)
        ->json();
        
        return $response[0];
    }
}