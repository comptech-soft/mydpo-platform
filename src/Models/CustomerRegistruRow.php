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
        'departament_id' => 'integer',
        'customer_register_id' => 'integer',
        'order_no' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_register_id',
        'register_id',
        'customer_id',
        'departament_id',
        'order_no',
        'status',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    function values() {
        return $this->hasMany(CustomerRegistruRowValue::class, 'row_id');
    }

    public static function doDelete($input, $record) {
        $record->values->delete();
        $record->delete();

        return $record;
    }

    public static function doInsert($input, $record) {

        $record = self::create([
            'customer_register_id' => $input['customer_register_id'],
            'customer_id' => $input['customer_id'],
            'register_id' => $input['register_id'],
            'departament_id' => $input['departament_id'],
            'order_no' => $input['order_no'],
            'status' => $input['status'],
            'props' => [
                'rowvalues' => $input['rowvalues'],
            ],
            
        ]);

        if(array_key_exists('rowvalues', $input))
        {
            foreach($input['rowvalues'] as $key => $value)
            {
                CustomerRegistruRowValue::create([
                    'row_id' => $record->id,
                    'column_id' => \Str::replace('col-', '', $key),
                    'value' => $value,
                ]);
            }
        }

        return $record;
    } 

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

}