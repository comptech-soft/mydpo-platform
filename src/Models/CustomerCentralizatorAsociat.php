<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Performers\CustomerRegistruAsociat\SaveAsociere;

class CustomerCentralizatorAsociat extends Model {

    protected $table = 'customers-centralizatoare-asociere';

    protected $casts = [
        'props' => 'json',
        'centralizator_id' => 'integer',
        'customer_id' => 'integer',
        'is_associated' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'centralizator_id',
        'is_associated',
        'props',
        'created_by',
        'updated_by',
    ];

}