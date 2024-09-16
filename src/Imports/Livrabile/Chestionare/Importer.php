<?php

namespace MyDpo\Imports\Livrabile\Chestionare;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\System\Translation; 

class Importer implements ToCollection {

    protected $lines = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function collection(Collection $rows) {

        dd($rows);
        
        $this->CreateLines($rows);

        $this->Process();
    }

    private function ValidRecord($record) {
        
        return !! $record['ro'];
    }

    private function RowToRecord($row) {
        return [
            'ro' => $row[1],
            'en' => $row[2],
            '__line' => $row['__line']
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