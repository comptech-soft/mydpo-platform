<?php

namespace MyDpo\Models\Customer\Registre;

use Illuminate\Database\Eloquent\Model;

class RowValue extends Model {
   
    protected $table = 'customers-registers-rows-values';

    protected $casts = [
        'row_id' => 'integer',
        'column_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'row_id',
        'column_id',
        'value',
        'type',
        'created_by',
        'updated_by'
    ];
    
}