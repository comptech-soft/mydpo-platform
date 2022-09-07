<?php

namespace MyDpo\Performers\Curs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;
use MyDpo\Models\Knolyx;

class GetKnolyxCourses extends Perform {

    public function GetCourses($page, $size) {

        $result = Knolyx::GetCourses($page, $size);

        dd($result);
    }

    public function Action() {

        $this->GetCourses(1, 1);

        

        
    }

}