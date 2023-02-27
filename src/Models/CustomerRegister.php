<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;
use MyDpo\Traits\NextNumber;

class CustomerRegister extends Model {

    use NextNumber;

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

    public $nextNumberColumn = 'number';

    public static function getItems($input) {
        return (new GetItems($input, self::query(), __CLASS__))->Perform();
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function GetRules($action, $input) {

        if($action == 'delete')
        {
            return NULL;
        }

        $result = [
            'customer_id' => 'required|exists:customers,id',
            'register_id' => 'required|exists:registers,id',
            'number' => 'required',
            'date' => [
                'required',
                'date',
                // new UniqueOrderService($input),
            ],
        ];
        return $result;
    }

    public static function nextNumberWhere($input) {
        return "(customer_id = " . $input['customer_id'] . ") AND (register_id = " . $input['register_id'] . ")";
    }



}