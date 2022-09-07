<?php

namespace MyDpo\Performers\Curs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\SysConfig;
use MyDpo\Models\Knolyx;

class GetKnolyxCourses extends Perform {

    public function Action() {

        $result = Knolyx::GetCourses();

        dd($result);
    }

}