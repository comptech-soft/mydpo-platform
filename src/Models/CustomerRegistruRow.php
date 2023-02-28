<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\DoAction;

class CustomerRegistruRow extends Model {

    protected $table = 'customers-registers-rows';

    protected $casts = [
        'props' => 'json',
        'register_id' => 'integer',
        'customer_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'register_id',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function doAction($action, $input) {

        dd($input);
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

}