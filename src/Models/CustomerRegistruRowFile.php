<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerRegistruRowFile extends Model {
   
    protected $table = 'customers-registers-rows-files';

    protected $casts = [
        'deleted' => 'integer',
        'row_id' => 'integer',
        'file' => 'json',
    ];

    protected $fillable = [
        'id',
        'row_id',
        'file',
        'created_by',
        'updated_by'
    ];

    
    
}