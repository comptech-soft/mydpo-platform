<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Performers\CustomerRegistruAsociat\SaveAsociere;

class CustomerRegister extends Model {

    protected $table = 'customers-registers';

    protected $casts = [
        'props' => 'json',
        'columns' => 'json',
        'register_id' => 'integer',
        'customer_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'responsabil_nume',
        'responsabil_functie',
        'customer_id',
        'register_id',
        'columns',
        'number',
        'date',
        'status',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // public static function saveAsociere($input) {
    //     return (new SaveAsociere($input))->Perform();
    // }

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

}