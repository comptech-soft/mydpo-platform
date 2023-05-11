<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Performers\CustomerCentralizatorRow\InsertRow;
use MyDpo\Performers\CustomerCentralizatorRow\DeleteRow;
use MyDpo\Performers\CustomerCentralizatorRow\SetRowsStatus;
use MyDpo\Performers\CustomerCentralizatorRow\SetRowsVisibility;

class CustomerCentralizatorRow extends Model {

    use Itemable;

    protected $table = 'customers-centralizatoare-rows';

    protected $casts = [
        'customer_centralizator_id' => 'integer',
        'customer_id' => 'integer',
        'centralizator_id' => 'integer',
        'department_id' => 'integer',
        'order_no' => 'integer',
        'deleted' => 'integer',
        'props' => 'json',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_centralizator_id',
        'customer_id',
        'centralizator_id',
        'department_id',
        'order_no',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $with = [
        'rowvalues',
    ];

    public function rowvalues() {
        return $this->hasMany(CustomerCentralizatorRowValue::class, 'row_id')->select(['id', 'row_id', 'column_id', 'value']);
    }

    public static function insertRow($input) {
        return (new InsertRow($input))->Perform();
    }

    public static function deleteRow($input) {
        return (new DeleteRow($input))->Perform();
    }

    public static function setRowsStatus($input) {
        return (new SetRowsStatus($input))->Perform();
    }

    public static function setRowsVisibility($input) {
        return (new SetRowsVisibility($input))->Perform();
    }

}