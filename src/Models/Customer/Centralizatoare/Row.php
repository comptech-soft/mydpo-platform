<?php

namespace MyDpo\Models\Customer\Centralizatoare;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Customer\Centralizatoare\Rowable;


// use MyDpo\Performers\Customer\Centralizatoare\Row\InsertRow;
// use MyDpo\Performers\Customer\Centralizatoare\Row\UpdateRow;
// use MyDpo\Performers\Customer\Centralizatoare\Row\DeleteRow;
// use MyDpo\Performers\Customer\Centralizatoare\Row\DeleteRows;
// use MyDpo\Performers\Customer\Centralizatoare\Row\SetRowsStatus;
// use MyDpo\Performers\Customer\Centralizatoare\Row\SetRowsVisibility;

class Row extends Model {

    use Itemable, Actionable, Rowable;

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
        'department',
    ];

    protected $withCount = [
        'files',
    ];
    
    public function files() {
        return $this->hasMany(RowFile::class, 'row_id');
    }

    public function DeleteValues() {
        RowValue::where('row_id', $this->id)->delete();
    }

    public static function PrepareActionInput($action, $input) {

        if( in_array($action, ['insert', 'update']) )
        {
            $input = [
                ...$input,
                'centralizator_id' => $input['tip_id'],
                'customer_centralizator_id' => $input['document_id'],
            ];
        }

        return $input;
    }
    
    // 

    // public function getDepartmentIdAttribute() {
    //     $column_id = $this->customercentralizator->department_column_id;
    //     $rowvalue = $this->rowvalues()->where('column_id', $column_id)->first();
 
    //     return !! $rowvalue ? $rowvalue->value : null;
    // }
 
    // public function customercentralizator() {
    //     return $this->belongsTo(CustomerCentralizator::class, 'customer_centralizator_id');
    // }
     
    // public function rowvalues() {
    //     return $this->hasMany(CustomerCentralizatorRowValue::class, 'row_id')->select(['id', 'row_id', 'column_id', 'value']);
    // }

    

    public function DuplicateValues($id, $new_customer_id){
        $rowvalues = RowValue::where('row_id', $this->id)->get();

        foreach($rowvalues as $i => $rowvalue)
        {
            $input = $rowvalue->toArray();
            $input['id'] = NULL;
            $input['row_id'] = $id;

            RowValue::create($input);
        }
    }

    

    // public static function insertRow($input) {
    //     return (new InsertRow($input))->Perform();
    // }

    // public static function updateRow($input) {
    //     return (new UpdateRow($input))->Perform();
    // }

    // public static function deleteRow($input) {
    //     return (new DeleteRow($input))->Perform();
    // }

    // public static function deleteRows($input) {
    //     return (new DeleteRows($input))->Perform();
    // }

    // public static function setRowsStatus($input) {
    //     return (new SetRowsStatus($input))->Perform();
    // }

    // public static function setRowsVisibility($input) {
    //     return (new SetRowsVisibility($input))->Perform();
    // }

    
    
}