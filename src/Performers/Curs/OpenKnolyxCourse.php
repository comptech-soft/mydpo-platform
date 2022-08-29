<?php

namespace MyDpo\Performers\Curs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;
use App\Models\User;
use MyDpo\Models\Knolyx;

class OpenKnolyxCourse extends Perform {

    public function Action() {

        $user = array_key_exists('user_id', $this->input) ? User::find($this->input['user_id']) : \Auth::user();

        if( ! $user )
        {
            throw new \Exception('User inexistent.');
        }
        
        /**
         * #1. PUT {{baseURL}}/public/api/v1/user/provision
         */
        Knolyx::CreateUser($user);

        // if( ! $user['k_id'] ) 
        // {
        //     throw new \Exception('Nu am utilizator Knolyx.');
        // }

        // /**
        //  * #2. GET {{baseURL}}/public/api/v1/business-rule/course/{{courseId}}
        //  */
        // $course_id = Curs::find($this->input['curs_id'])->k_id;
        // $courseRole = self::GetCourseRole($course_id);

        // /**
        //  * #3. POST {{baseURL}}/public/api/v1/business-rule/course/{{courseId}}
        //  */
        // if( ! array_key_exists('USER', $courseRole['associations']) )
        // {
        //     $courseRole['associations']['USER'] = [];
        // }
        
        // if( ! in_array($user['k_id'], $courseRole['associations']['USER']) )
        // {
        //     $courseRole['associations']['USER'][] = $user['k_id'];
        //     self::SetCourseRole($course_id, $courseRole);
        // }
    }

}