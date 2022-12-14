<?php

namespace MyDpo\Imports\CustomerCursParticipant;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\CustomerCursParticipant;

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

        $input = [
            'customer_curs_id' => $this->input['customer_curs_id'],
            'customer_id' => $this->input['customer_id'],
            'platform' => config('app.platform'),
            'last_name' => $row[0],
            'first_name' => $row[1],
            'functiie' => $row[2],
            'data' => $row[3],
            
        ];

        CustomerCursParticipant::create($input);

    }    

}