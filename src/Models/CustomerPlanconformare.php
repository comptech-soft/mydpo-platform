<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Performers\CustomerPlanconformare\GetNextNumber;
// use MyDpo\Performers\CustomerCentralizator\Export;
// use MyDpo\Performers\CustomerCentralizator\Import;
// use MyDpo\Performers\CustomerCentralizator\SaveSettings;
// use MyDpo\Performers\CustomerCentralizator\SetAccess;

class CustomerPlanconformare extends Model {

    use Itemable, Actionable;

    protected $table = 'customers-planuri-conformare';

    protected $casts = [
        'props' => 'json',
        'current_lines' => 'json',
        'customer_id' => 'integer',
        'department_id' => 'integer',
        'visibility' => 'integer',
        'year' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'centralizator_id',
        'department_id',
        'visibility',
        'number',
        'year',
        'responsabil_nume',
        'responsabil_functie',
        'props',
        'current_lines',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        // 'columns',
        // 'visible',
        // 'visible_column_id',
        // 'status_column_id',
        // 'department_column_id'
    ];

    protected $with = [
        'department',
    ];

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id')->select(['id', 'departament']);
    }

    public static function doInsert($input, $record) { 
        $current_lines = PlanConformare::whereNull('parent_id')->get()->toArray();

        $input = [
            ...$input,
            'current_lines' => $current_lines,
        ];

        $record = self::create($input);

        return $record;
    }

    public static function doDuplicate($input, $record) {

       

    }

    public static function doDelete($input, $record) {
   
        $record->delete();
        return $record;
    }

    // public static function doExport($input) {
    //     return (new Export($input))->Perform();
    // }

    // public static function saveSettings($input) {
    //     return (new SaveSettings($input))->Perform();
    // }

    // public static function setAccess($input) {
    //     return (new SetAccess($input))->Perform();
    // }

    // public static function doImport($input) {
    //     return (new Import($input))->Perform();
    // }

    protected function DuplicateRows($id, $department_ids, $new_customer_id) {

        // $rows = CustomerCentralizatorRow::where('customer_centralizator_id', $this->id)->get();

        // foreach($rows as $i => $row)
        // {

        //     $department_id = !! $row->department_id ? $row->department_id : 'none';

        //     if( in_array($department_id, $department_ids) )
        //     {
        //         $newrow = $row->replicate(['files_count']);

        //         $newrow->customer_centralizator_id = $id;
        //         $newrow->save();

        //         $row->DuplicateValues($newrow->id, $new_customer_id);
        //     }
        // }

    }

    // public function DeleteRows() {
    //     $rows = CustomerCentralizatorRow::where('customer_centralizator_id', $this->id)->get();
    //     foreach($rows as $i => $row)
    //     {
    //         $row->DeleteValues();

    //         $row->delete();
    //     }
    // }

    public static function getNextNumber($input) {
        return (new GetNextNumber($input))->Perform();
    }

    // public static function GetQuery() {

    //     $q = self::query();

    //     if(config('app.platform') == 'b2b')
    //     {
    //         $q = $q->where('visibility', 1);
    //     }
        
    //     return $q;
    // }

}