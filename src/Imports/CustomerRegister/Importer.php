<?php

namespace MyDpo\Imports\CustomerRegister;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\CustomerRegister;

class Importer implements ToCollection {

    public $input = NULL;
    public $registru = NULL;

    public function __construct($input) {
        $this->input = $input;
        $this->registru = CustomerRegister::where('id', $this->input['id'])->first();
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

        dd($this->register, $this->input, $row);

        CustomerCursParticipant::create($input);

    }    

}