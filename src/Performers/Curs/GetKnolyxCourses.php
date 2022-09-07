<?php

namespace MyDpo\Performers\Curs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;
use MyDpo\Models\Knolyx;

class GetKnolyxCourses extends Perform {

    public function GetCourses($page, $size) {

        $result = Knolyx::GetCourses($page, $size);

        \Log::info($page . ' - ' . $size . ' - ' . count($result['list']));
        if( count($result['list']) > 0 )
        {
            $this->GetCourses($page + 1, $size);
        }
        
    }

    public function Action() {

        $this->GetCourses(1, 1);

        

        
    }

}