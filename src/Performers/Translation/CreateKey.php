<?php

namespace MyDpo\Performers\Translation;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Translation;

class CreateKey extends Perform {

    public function Action() {

        foreach($this->input['keys'] as $i => $key)
        {
            if(!! $key)
            {
                $record = Translation::where('ro', $key)->first();
                if( ! $record )
                {
                    Translation::create([
                        'ro' => $key,
                    ]);
                }
            }
        }
    }

}