<?php

namespace MyDpo\Performers\Curs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;
use MyDpo\Models\Knolyx;
use MyDpo\Models\Curs;

class GetKnolyxCourses extends Perform {

    public function GetCourses($page, $size) {

        Curs::whereNotNull('k_id')->update([
            'deleted' => 1,
        ]);

        $result = Knolyx::GetCourses($page, $size);

        if( count($result['list']) > 0 )
        {
            Curs::saveCoursesFromKnolyx($result['list']);
            $this->GetCourses($page + 1, $size);
        }
        
    }

    public function Action() {
        $this->GetCourses(0, 20);
    }

}