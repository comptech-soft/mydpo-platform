<?php

namespace MyDpo\Performers\Curs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;
use App\Models\User;
use MyDpo\Models\Knolyx;
use MyDpo\Models\Curs;

class OpenKnolyxCourse extends Perform {

    public function Action() {

        $user = array_key_exists('user_id', $this->input) ? User::find($this->input['user_id']) : \Auth::user();

        if( ! $user )
        {
            throw new \Exception('User inexistent.');
        }
        
        $user = Knolyx::CreateUser($user);

        if( ! $user->k_id )
        {
            throw new \Exception('User Knolyx inexistent.');
        }

        $course = Curs::find($this->input['curs_id']);

        if( ! $course || ! $course->k_id)
        {
            throw new \Exception('Curs inexistent.');
        }

        $courseRole = Knolyx::GetCourseRole($course);

        dd($courseRole);

 

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