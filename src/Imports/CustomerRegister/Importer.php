<?php

namespace MyDpo\Imports\CustomerRegister;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
// use MyDpo\Models\CustomerCursParticipant;

class Importer implements ToCollection {

    public $input = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function collection(Collection $rows) {
        foreach($rows as $i => $row)
        {
            if($i > 0)
            {
                $this->processRow($row);
            }
        }
    }

    private function processRow($row) {

        dd($this->input, $row);

        CustomerCursParticipant::create($input);

    }    

}