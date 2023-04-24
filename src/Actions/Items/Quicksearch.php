<?php

namespace ComptechSoft\MyHelpers\Performers\Data;

class Quicksearch {

    protected $query = NULL;
    protected $raw = '';

    public function __construct($query) {

        $this->query = $query;

    }

    public function Search($search) {


        $this->raw = '';
        
        if( ! ($searched = $search['searched']) )
        {
            return [
                'raw' => config('app.env') != 'prod' ? $this->raw : NULL,
                'count' => $this->query->count(),
            ];
        }

        
        
        foreach($words = explode(',', $searched) as $i => $value)
        {
            $value = trim($value);

            if($value)
            {
                if($value[0] == '*') 
                {
                    //  Daca incepem cu * cautam si in interior
                    $value = \DB::connection()->getPdo()->quote('%' . substr($value, 0) . '%');
                }
                else 
                {
                    // Nu incepe cu * ==> cautam doar la inceput
                    $value = \DB::connection()->getPdo()->quote($value . '%');
                }
            
                foreach($search['columns'] as $j => $column)
                {
                    $this->raw .= "(" . $column['name'] . " LIKE " . $value . ") OR ";
                }
            }
        }
        
        if($this->raw)
        {
            $this->raw = '(' . substr($this->raw, 0, strlen($this->raw) - 4) . ')';
            $this->query->whereRaw($this->raw);
        }

        return [
            'raw' => config('app.env') != 'prod' ? $this->raw : NULL,
            'count' => $this->query->count(),
        ];

    }
}