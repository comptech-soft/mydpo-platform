<?php

namespace MyDpo\Models\Customer\Centralizatoare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;

use MyDpo\Performers\Customer\Centralizatoare\Row\InsertRow;
use MyDpo\Performers\Customer\Centralizatoare\Row\UpdateRow;
use MyDpo\Performers\Customer\Centralizatoare\Row\DeleteRow;
use MyDpo\Performers\Customer\Centralizatoare\Row\DeleteRows;
use MyDpo\Performers\Customer\Centralizatoare\Row\SetRowsStatus;
use MyDpo\Performers\Customer\Centralizatoare\Row\SetRowsVisibility;

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
        'visibility' => 'integer',
        'props' => 'json',
        'tooltip' => 'json',
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
        'deleted_by',
        'visibility',
        'status',
        'action_at',
        'tooltip',
    ];

    protected $appends = [
        'human_status',
    ];

    protected $with = [
        'rowvalues',
    ];

    protected $withCount = [
        'files',
    ];

    public function getHumanStatusAttribute() {
        return __FILE__;
    }

    // public function getDepartmentIdAttribute() {
    //     $column_id = $this->customercentralizator->department_column_id;
    //     $rowvalue = $this->rowvalues()->where('column_id', $column_id)->first();
 
    //     return !! $rowvalue ? $rowvalue->value : null;
    // }
 
    // public function customercentralizator() {
    //     return $this->belongsTo(CustomerCentralizator::class, 'customer_centralizator_id');
    // }
     
    public function rowvalues() {
        return $this->hasMany(CustomerCentralizatorRowValue::class, 'row_id')->select(['id', 'row_id', 'column_id', 'value']);
    }

    public function files() {
        return $this->hasMany(CustomerCentralizatorRowFile::class, 'row_id');
    }

    // public function DuplicateValues($id, $new_customer_id){

    //     $rowvalues = CustomerCentralizatorRowValue::where('row_id', $this->id)->get();
    //     $column_id = $this->customercentralizator->department_column_id;

    //     foreach($rowvalues as $i => $rowvalue)
    //     {
    //         $newrowvalue = $rowvalue->replicate();

    //         $newrowvalue->row_id = $id;

    //         if($newrowvalue->column_id == $column_id)
    //         {
    //             if(!! $rowvalue->value)
    //             {
    //                 $department = CustomerDepartment::CreateIfNecessary(
    //                     $this->customercentralizator->customer_id, 
    //                     $new_customer_id, 
    //                     $rowvalue->value
    //                 );

    //                 $newrowvalue->value = $department->id;
    //             }
    //         }

    //         $newrowvalue->save();
    //     }
    // }

    // public function DeleteValues() {
    //     CustomerCentralizatorRowValue::where('row_id', $this->id)->delete();
    // }

    public static function insertRow($input) {
        return (new InsertRow($input))->Perform();
    }

    public static function updateRow($input) {
        return (new UpdateRow($input))->Perform();
    }

    public static function deleteRow($input) {
        return (new DeleteRow($input))->Perform();
    }

    public static function deleteRows($input) {
        return (new DeleteRows($input))->Perform();
    }

    public static function setRowsStatus($input) {
        return (new SetRowsStatus($input))->Perform();
    }

    public static function setRowsVisibility($input) {
        return (new SetRowsVisibility($input))->Perform();
    }
    
}