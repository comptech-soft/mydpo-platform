<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;

class CustomerRegistruUser extends Model {

    protected $table = 'customers-registers-users';

    protected $casts = [
        'props' => 'json',
        'departamente' => 'json',
        'register_id' => 'integer',
        'customer_id' => 'integer',
        'customer_registru_id' => 'integer',
        'user_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_registru_id',
        'customer_id',
        'register_id',
        'user_id',
        'departamente',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}