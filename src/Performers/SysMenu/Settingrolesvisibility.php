<?php

namespace MyDpo\Performers\SysMenu;

use MyDpo\Helpers\Perform;
use MyDpo\Models\System\SysMenuRole;
// use MyDpo\Models\System\SysActionRole;

class Settingrolesvisibility extends Perform {

    public function Action() {

        foreach($this->roles as $i => $input)
        {
            dd($this->menu_id, $input);
        }

        // dd($this->input);
    }

}