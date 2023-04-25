<?php

namespace MyDpo\Performers\Traits;

use MyDpo\Actions\Perform;

class Reorder extends Perform {

    public function Action() {
       
        foreach($this->items as $i => $item)
        {
            $sql = "UPDATE `" . $this->table . "` SET `" . $this->field . "`=" . $item['order_no'] . " WHERE `id`=" . $item['id'] . ";";

            \DB::statement($sql);
            
        }
    }

} 