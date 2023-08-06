<?php

namespace MyDpo\Models\Customer\Centralizator;

use Illuminate\Database\Eloquent\Model;

class CustomerCentralizatorRowValue extends Model {

    protected $table = 'customers-centralizatoare-rows-values';

    protected $casts = [
        'row_id' => 'integer',
        'column_id' => 'integer',
        'props' => 'json',
        // 'column' => 'json',
    ];

    protected $fillable = [
        'id',
        'row_id',
        'column_id',
        'value',
        'column',
        'props',
    ];

}