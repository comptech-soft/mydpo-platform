<?php

namespace MyDpo\Performers\SysMenu;

use MyDpo\Helpers\Perform;
use MyDpo\Models\System\SysMenuRole;


class GetVisibilities extends Perform {

    public function Action() {
        
        $records = SysMenuRole::where('role_id', $this->role_id)->wherePlatform($this->platform)->get();

        $this->payload = $records;
    }

}