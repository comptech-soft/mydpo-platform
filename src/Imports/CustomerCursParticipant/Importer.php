<?php

namespace MyDpo\Imports\CustomerCursParticipant;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class Importer implements ToCollection {

    
    public function __construct() {
    }



    public function collection(Collection $rows) {
        
        foreach($rows as $i => $row)
        {
            dd($i, $row);
        }
    }

    

}