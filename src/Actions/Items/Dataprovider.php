<?php

namespace MyDpo\Actions\Items;

use MyDpo\Actions\Perform;

class Dataprovider extends Perform {

    protected $model = NULL;

    protected $query = NULL;
    
    protected $Quicksearch = NULL;
    protected $Initialfilter = NULL;
    protected $Globalfilter = NULL;
    protected $Sorter = NULL;

    public function __construct($input, $query, $model) {
        parent::__construct($input);

        $this->model = $model;

        $this->query = $query;

        $this->SetSuccessMessage('Getting items succesfully');
        $this->SetExceptionMessage([\Exception::class => NULL]);

        $this->Initialfilter = new Initialfilter($this->query);
        $this->Globalfilter = new Globalfilter($this->query);
        $this->Quicksearch = new Quicksearch($this->query);
        $this->Sorter = new Sorter($this->query);
    }

    protected function ColumnSearchByString($column) {
        $value = trim($column['value']);
        $field = $column['field'];
        if($value)
        {
            if($value[0] == '*') //  Daca incepem cu * cautam si in interior
            {
                $value = \DB::connection()->getPdo()->quote('%' . substr($value, 1) . '%');
            }
            else // Nu incepe cu * ==> cautam doar la inceput
            {
                $value = \DB::connection()->getPdo()->quote($value . '%');
            }
            $this->columnsSearchWhereRaw .= "(" . $field . " LIKE " . $value . ") AND ";
        }
    }

    protected function ColumnSearchByDropdown($column) {

        $value = trim($column['value']);
        $field = $column['field'];

        if( (strlen($value) > 0) && (($value > 0) || ! is_null($value)) )
        {
            $this->columnsSearchWhereRaw .= "(" . $field . "='" . $value . "') AND ";
        }
    }

    protected function ColumnSearchBy() {
        if(array_key_exists('columns_search', $this->input))
        {
            $this->columnsSearchWhereRaw = '';

            foreach($this->input['columns_search'] as $i => $column)
            {
                $this->{'ColumnSearchBy' . ucfirst($column['type'])}($column);
            }

            if($this->columnsSearchWhereRaw)
            {
                $this->columnsSearchWhereRaw = '(' . substr($this->columnsSearchWhereRaw, 0, strlen($this->columnsSearchWhereRaw) - 5) . ')';
                $this->query->whereRaw($this->columnsSearchWhereRaw);
            }
        }
    }

    public function Action() {

        $initialfilter = $this->Initialfilter->Filter($this->initialfilter);

        if( ! $this->per_page || ($this->per_page < 0) )
        {
            $this->per_page = $initialfilter['count'];
        }

        if(!! $this->select)
        {
            $this->query->select($this->select);
        }


        $this->payload = [
            'recordcount' => call_user_func([$this->model, 'count']), 

            'initialfilter' => $initialfilter,

            'quicksearch' => $this->Quicksearch->Search($this->search),

            'globalfilter' => $this->Globalfilter->Filter($this->globalfilter),

            'sort' => $this->Sorter->Sort($this->sort),

            'paginator' => $this->query->paginate($this->per_page),

            'input' => $this->input,

        ];
    }
} 