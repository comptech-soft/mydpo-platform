<?php

namespace MyDpo\Models\Customer\Centralizatoare;

use Illuminate\Database\Eloquent\Model;

class RowValue extends Model {

    protected $table = 'customers-centralizatoare-rows-values';

    protected $casts = [
        'row_id' => 'integer',
        'column_id' => 'integer',
        'props' => 'json',
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