<?php

namespace MyDpo\Helpers\Performers\Datatable;

use MyDpo\Helpers\Perform;

class GetItems extends Perform {

    protected $model = NULL;

    protected $query = NULL;

    protected $searchWhereRaw = '';
    protected $columnsSearchWhereRaw = '';
    protected $orderByRaw = '';
    protected $permanentWhereRaw = '';
    protected $globalSearchWhereRaw = '';

    public function __construct($input, $query, $model) {
        parent::__construct($input);

        $this->model = $model;

        $this->query = $query;

        $this->SetSuccessMessage('Getting items succesfully');
        $this->SetExceptionMessage([\Exception::class => NULL]);
    }

    protected function SearchBy() {
        if(array_key_exists('search', $this->input))
        {
            $this->searchWhereRaw = '';
            $search = $this->input['search'];
            if($search['value'])
            {
                foreach(explode(',', $search['value']) as $i => $value)
                {
                    $value = trim($value);
                    if($value)
                    {
                        if(TRUE || ($value[0] == '*')) //  Daca incepem cu * cautam si in interior
                        {
                            $value = \DB::connection()->getPdo()->quote('%' . substr($value, 0) . '%');
                        }
                        else // Nu incepe cu * ==> cautam doar la inceput
                        {
                            $value = \DB::connection()->getPdo()->quote($value . '%');
                        }
                        
                        foreach($search['columns'] as $j => $field)
                        {
                            $this->searchWhereRaw .= "(" . $field['name'] . " LIKE " . $value . ") OR ";
                        }
                    }
                }
            }
            if($this->searchWhereRaw)
            {
                $this->searchWhereRaw = '(' . substr($this->searchWhereRaw, 0, strlen($this->searchWhereRaw) - 4) . ')';
                $this->query->whereRaw($this->searchWhereRaw);
            }
        }
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

    protected function WheresBy() {
        if(array_key_exists('wheres', $this->input) && ($wheres = $this->input['wheres']))
        {
            $this->globalSearchWhereRaw = '';
            foreach($wheres as $key => $where) 
            {
                if($where)
                {
                    $this->globalSearchWhereRaw .= ($where . ' AND ');
                }
            }
            if($this->globalSearchWhereRaw)
            {
                $this->globalSearchWhereRaw = '(' . substr($this->globalSearchWhereRaw, 0, strlen($this->globalSearchWhereRaw) - 5) .')';              
                $this->query->whereRaw($this->globalSearchWhereRaw); 
            }
        }
    }

    protected function Sort() {
        if(array_key_exists('sort', $this->input))
        {
            $this->orderByRaw = '';
            foreach($this->input['sort'] as $key => $order) {

                if($order['sort_column'])
                {
                    $field = $order['sort_column'];
                }
                else
                {
                    $field = $order['field'];
                }
                $this->orderByRaw .= ($field . ' ' . strtoupper($order['direction']) . ', ');
            }
            if($this->orderByRaw)
            {
                $this->orderByRaw = substr($this->orderByRaw, 0, strlen($this->orderByRaw) - 2);
                $this->query->orderByRaw($this->orderByRaw); 
            }
        }
    }

    protected function PermanentFilter() {
        
        if(array_key_exists('permanent_filters', $this->input))
        {
            $this->permanentWhereRaw = '';
            foreach($this->input['permanent_filters'] as $key => $whereRaw) 
            {
                $this->permanentWhereRaw .= '(' . $whereRaw . ') AND ';
            }
            if($this->permanentWhereRaw)
            {
                $this->permanentWhereRaw = '(' . substr($this->permanentWhereRaw, 0, strlen($this->permanentWhereRaw) - 5) .')';
                $this->query->whereRaw($this->permanentWhereRaw); 
            }
        }
    }

    public function Action() {
      
        $this->PermanentFilter();

        $initialCount = $this->query->count();

        if(! array_key_exists('per_page', $this->input) || ($this->input['per_page'] == -1))
        {
            $this->input['per_page'] = $initialCount;
        }

        /** search by */
        $this->SearchBy();

        /** column filtering */
        $this->ColumnSearchBy();

        /** global filtering */
        $this->WheresBy();

        /** sort */
        $this->Sort();

        $paginator = $this->query->paginate($this->input['per_page']);

        $this->payload = [
            'paginator' => $paginator,
            'raws' => [
                'search' => $this->searchWhereRaw,
                'sort' => $this->orderByRaw,
                'columnsearch' => $this->columnsSearchWhereRaw,
                'permanentFilter' => $this->permanentWhereRaw,
                'globalsearch' => $this->globalSearchWhereRaw,
            ],
            'counts' => [
                'physical_record' => call_user_func([$this->model, 'count']), 
                'query_initial' => $initialCount,
            ], 
        ];
    }
} 