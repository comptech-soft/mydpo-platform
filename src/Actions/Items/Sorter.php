<?php

namespace ComptechSoft\MyHelpers\Performers\Data;

class Sorter {

    protected $query = NULL;
    protected $raw = '';

    public function __construct($query) {

        $this->query = $query;

    }

    public function Sort($columns) {

        $this->raw = '';

        if( ! $columns || (count($columns) == 0) )
        {
            return [
                'raw' => config('app.env') != 'prod' ? $this->raw : NULL,
                'count' => $this->query->count(),
            ];
        }

        foreach($columns as $i => $item) 
        {

            $field = $item['column'];

            $this->raw .= ($field . ' ' . strtoupper($item['direction']) . ', ');
        }

        if($this->raw)
        {
            $this->raw = substr($this->raw, 0, strlen($this->raw) - 2);
            $this->query->orderByRaw($this->raw); 
        }

        return [
            'raw' => config('app.env') != 'prod' ? $this->raw : NULL,
            'count' => $this->query->count(),
        ];

    }
}