<?php

namespace MyDpo\Imports\Customer\Entities\Account;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\Customer\Departments\Department;

class Importer implements ToCollection {

    protected $lines = NULL;

    /**
     * departamentele clientului
     */
    public $departamente = NULL;

    public function __construct($input) {
        $this->input = $input;

        $this->departamente = Department::where('customer_id', $this->input['customer_id'])->pluck('id', 'departament')->toArray();
    }

    public function collection(Collection $rows) {

        $this->CreateLines($rows);

        $this->Process();
    }

    private function ValidRecord($record) {
        
        return !! $record['last_name'] && !! $record['first_name'] && !! $record['email'];
    }

    private function RowToRecord($row) {

        dd($row);
        
        return [
            'last_name' => trim($row[0]),
            'first_name' => trim($row[1]),
            'email' =>  trim($row[2]),
        ];
    }

    private function ProcessLine($line) {

        $input = collect($line)->except(['__line'])->toArray();

        $record = Translation::where('ro', $input['ro'])->first();

        if( !! $record )
        {
            $record->update($input);
        }
        else
        {
            $record = Translation::create($input);
        }

    }    

    protected function Process() {

        dd($this->lines);

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
                '__line' => $i,
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