<?php

namespace ComptechSoft\MyHelpers\Performers\Data;

class Initialfilter {

    protected $query = NULL;
    protected $raw = '';

    public function __construct($query) {

        $this->query = $query;

    }

    public function Filter($wheres) {

        $this->raw = '';

        if( ! $wheres || (count($wheres) == 0) )
        {
            return [
                'raw' => config('app.env') != 'prod' ? $this->raw : NULL,
                'count' => $this->query->count(),
            ];
        }

        foreach($wheres as $i => $whereRaw) 
        {
            $this->raw .= '(' . $whereRaw . ') AND ';
        }

        if($this->raw)
        {
            $this->raw = '(' . substr($this->raw, 0, strlen($this->raw) - 5) .')';
            $this->query->whereRaw($this->raw); 
        }

        return [
            'raw' => config('app.env') != 'prod' ? $this->raw : NULL,
            'count' => $this->query->count(),
        ];

    }
}