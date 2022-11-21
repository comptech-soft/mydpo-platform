<?php

namespace MyDpo\Performers\Database;

use MyDpo\Helpers\Perform;

class UpdateField extends Perform {

    public function Action() {
        
        $record = \DB::table($this->input['table'])
            ->where('id', $this->input['id'])
            ->update([
                $this->input['field'] => $this->input['value'],
            ]);

        $this->payload = $record->refresh();
    }

} 