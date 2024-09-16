<?php

namespace MyDpo\Imports\Livrabile\Chestionare;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\Livrabile\Categories\Category;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\TipIntrebare;

class Importer implements ToCollection {

    protected $lines = NULL;

    protected $input = [];

    protected $target = [];

    protected $questions = [];

    public function __construct($input) {
        $this->input = $input;
    }

    public function collection(Collection $rows) {

        $this->PrepareChestionarToImport($rows);
        
        // $this->CreateLines($rows);

        // $this->Process();
    }

    public function PrepareChestionarToImport($rows)
    {
        
        $row = $rows[1];

        $this->target = [
            'name' => $row[1],
            'category_id' => Category::CreateIfNotExists($row[2], 'chestionare')->id,
            'subject' => $row[3],
            'body' => $row[4],
            'visibility' => $row[5],
            'description' => $row[6],
            'date_from' => $row[7],
            'date_to' => $row[8],
            'on_chestionare' => $row[9],
            'on_gap' => $row[10],
            'on_reevaluare' => $row[11],
            'questions' => [],
        ];

        $raw_questions = $this->PrepareRawQuestionsToImport($rows->filter(function($row, $i){
            return ($i >= 4);
        })->values()->toArray());

        $this->target['questions'] = $this->PrepareQuestionsToImport($raw_questions, 0);

        dd($this->target);
    }

    public function PrepareOneRawQuestionsToImport($raw_question, $index, $level)
    {
        $question = [];
        $options = [];
        $children = [];

        for($line = 0; $line < count($raw_question); $line++)
        {
            if($line == 0)
            {
                $question = [
                    'parent_id' => NULL,
                    'order_no' => $index,
                    'question_type_id' => $raw_question[$line][2],
                    'question_text' => $raw_question[$line][3],
                    'activate_on_answer_id' => TipIntrebare::findByShortName($raw_question[$line][8])->id,
                    'score' => $raw_question[$line][5],
                    'time_limit' => $raw_question[$line][6],
                    'is_required' => $raw_question[$line][4],
                    'options' => [],
                    'children' => [],
                ];
            }
            else
            {
                if($raw_question[$line][0] == 'O')
                {
                    $options[] = $raw_question[$line]; 
                } 
                else
                {
                    $children = array_slice($raw_question, $line);
                    $line = count($raw_question);
                }
            }
        }

        foreach($options as $i => $option)
        {
            $question['options'][] = [
                'answer_text' => $option[8],
            ];
        }

        if( count($children) > 0 )
        {
            $question['children'] = $this->PrepareQuestionsToImport([$children], $level + 1);
        }

        return $question;
    }

    public function PrepareQuestionsToImport(array $raw_questions, $level): array
    {
        $questions = [];

        foreach($raw_questions as $i => $raw_question)
        {
            $questions[] = $this->PrepareOneRawQuestionsToImport($raw_question, $i + 1, $level);
        }

        return $questions;
    }

    public function PrepareRawQuestionsToImport(array $rows): array
    {
        $nrq = 0; 
        $items[0] = [];
        for($i = 0; $i < count($rows); $i++)
        {
            if(!! $rows[$i][0])
            {
                $items[$nrq][] = $rows[$i];
            }
            else
            {
                $nrq++;
                $items[$nrq] = [];
            }
        }

        return $items;
    }

    // private function ValidRecord($record) {
        
    //     return !! $record['ro'];
    // }

    // private function RowToRecord($row) {
    //     return [
    //         'ro' => $row[1],
    //         'en' => $row[2],
    //         '__line' => $row['__line']
    //     ];
    // }

    // private function ProcessLine($line) {

    //     $input = collect($line)->except(['__line'])->toArray();

    //     $record = Translation::where('ro', $input['ro'])->first();

    //     if( !! $record )
    //     {
    //         $record->update($input);
    //     }
    //     else
    //     {
    //         $record = Translation::create($input);
    //     }

    // }    

    // protected function Process() {
    //     foreach($this->lines as $i => $line) 
    //     {
    //         $this->ProcessLine($line);
    //     }
    // }

    // protected function CreateLines($rows) {
    //     /**
    //      * Din input se ia start_row = linia de inceput
    //      */
    //     $start_row = 1 * $this->input['start_row'];

    //     $this->lines = $rows->map(function($row, $i){

    //         /**
    //          * Se ataseaza numarul de linie
    //          */
    //         return [
    //             ...$row,
    //             '__line' => $i,
    //         ];

    //     })->filter( function($row, $i) use ($start_row) {
    //         /**
    //          * Se incepe de la linia $start_row
    //          */
    //         return $i >= $start_row; 
        
    //     })->map( function($row, $i) {

    //         /**
    //          * Se convertesc randurile la tabela din BD
    //          */
    //         return $this->RowToRecord($row);
        
    //     })->filter( function($record, $i) {

    //         /**
    //          * Logica de validare a recordurilor
    //          */
    //         return $this->ValidRecord($record);

    //     })->values()->toArray();
    // }

}