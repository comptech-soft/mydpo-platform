<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Performers\CustomerRegistruAsociat\SaveAsociere;

class CustomerRegistruAsociat extends Model {

    protected $table = 'customers-registers-asociate';

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

    // public static function saveAsociere($input) {
    //     return (new SaveAsociere($input))->Perform();
    // }

    // public static function getItems($input) {
    //     return (new GetItems($input, self::query(), __CLASS__))->Perform();
    // }

}