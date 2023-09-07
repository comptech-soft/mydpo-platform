<?php

namespace MyDpo\Imports\Customer\Livrabile\ELearning\Participant;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\Customer\ELearning\CustomerCursParticipant; 
use MyDpo\Models\Customer\ELearning\CustomerCurs; 

class Importer implements ToCollection {

    protected $lines = NULL;
    protected $input = NULL;
    
    public function __construct($input) {
        $this->input = $input;
    }

    public function collection(Collection $rows) {
        $this->CreateLines($rows);
        $this->Process();
        CustomerCurs::Sync($this->input['customer_id']);
    }

    private function ValidRecord($record) {
        return true;
    }

    private function RowToRecord($row) {
        return [
            'customer_curs_id' => $this->input['customer_curs_id'],
            'customer_id' => $this->input['customer_id'],
            'platform' => config('app.platform'),
            'data' => $row[1],
            'last_name' => $row[2],
            'first_name' => $row[3],
            'functiie' => $row[4],
        ];
    }

    private function ProcessLine($line) {
        CustomerCursParticipant::create($line);
    }    

    protected function Process() {
        foreach($this->lines as $i => $line) 
        {
            $this->ProcessLine($line);
        }
    }

    protected function CreateLines($rows) {
        /**
         * Din input se ia start_row = linia de inceput
         */
        $start_row = 1 * $this->input['start_row'];

        $this->lines = $rows->map(function($row, $i){

            /**
             * Se ataseaza numarul de linie
             */
            return [
                ...$row,
            ];

        })->filter( function($row, $i) use ($start_row) {
            /**
             * Se incepe de la linia $start_row
             */
            return $i >= $start_row; 
        
        })->map( function($row, $i) {

            /**
             * Se convertesc randurile la tabela din BD
             */
            return $this->RowToRecord($row);
        
        })->filter( function($record, $i) {

            /**
             * Logica de validare a recordurilor
             */
            return $this->ValidRecord($record);

        })->values()->toArray();
    }

}