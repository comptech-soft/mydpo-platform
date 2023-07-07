<?php

namespace MyDpo\Performers\SysMenu;

use MyDpo\Helpers\Perform;
use MyDpo\Models\System\SysMenuRole;

class Settingrolesvisibility extends Perform {

    public function Action() {

        foreach($this->roles as $i => $item)
        {
            
            foreach(collect($item)->except(['role_id', 'slug'])->toArray() as $key => $data)
            {
                $input = [
                    'menu_id' => $this->menu_id,
                    'role_id' => $item['role_id'],
                    'platform' => $key,
                    'visible' => $data['visible'],
                    'disabled' => $data['disabled'],
                ];

                SysMenuRole::CreateOrUpdate($input);
            }
        }

    }

}