<?php

namespace MyDpo\Performers\SysMenu;

use MyDpo\Helpers\Perform;
use MyDpo\Models\System\SysMenuRole;
use MyDpo\Models\System\SysActionRole;

class GetVisibilities extends Perform {

    public function Action() {

        $this->payload = [
            'menus' => SysMenuRole::where('role_id', $this->role_id)->wherePlatform($this->platform)->get(),
            'actions' => SysActionRole::where('role_id', $this->role_id)->get(),
            
        ];
    }

}