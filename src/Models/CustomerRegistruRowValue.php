<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRegistruRowValue extends Model {
   
    protected $table = 'customers-registers-rows-values';

    protected $casts = [
        'deleted' => 'integer',
        'row_id' => 'integer',
        'column_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'row_id',
        'column_id',
        'deleted',
        'value',
        'created_by',
        'updated_by'
    ];
    
}