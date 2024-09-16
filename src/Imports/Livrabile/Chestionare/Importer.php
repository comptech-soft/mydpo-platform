<?php

namespace MyDpo\Imports\Livrabile\Chestionare;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\Livrabile\Categories\Category;
use MyDpo\Models\Nomenclatoare\Livrabile\Chestionare\TipIntrebare;
use MyDpo\Models\Livrabile\Chestionare\Chestionar;

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

        Chestionar::CreateQuestionarFromImport($this->target);

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
                    'question_type_id' => TipIntrebare::findByShortName($raw_question[$line][2])->id,
                    'question_text' => $raw_question[$line][3],
                    'activate_on_answer_id' => $raw_question[$line][9],
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

}