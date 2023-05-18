<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;

class CustomerCentralizatorRowFile extends Model {

    use Itemable;

    protected $table = 'customers-centralizatoare-rows-files';

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