<?php

namespace MyDpo\Performers\Translation;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Translation;
class CreateKey extends Perform {

    public function Action() {
        $record = Translation::where('ro', $this->input['key'])->first();

        if( ! $record )
        {
            Translation::create([
                'ro' => $this->input['key'],
            ]);
        }
    }

}