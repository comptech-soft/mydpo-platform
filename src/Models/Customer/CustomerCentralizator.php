<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
// use MyDpo\Traits\Actionable;
// use MyDpo\Performers\CustomerCentralizator\GetNextNumber;
// use MyDpo\Performers\CustomerCentralizator\Export;
// use MyDpo\Performers\CustomerCentralizator\Import;
// use MyDpo\Performers\CustomerCentralizator\SaveSettings;
// use MyDpo\Performers\CustomerCentralizator\SetAccess;

class CustomerCentralizator extends Model {

    use Itemable, Actionable;

    protected $table = 'customers-centralizatoare';

    protected $casts = [
        'props' => 'json',
        'current_columns' => 'json',
        'customer_id' => 'integer',
        'centralizator_id' => 'integer',
        'department_id' => 'integer',
        'visibility' => 'integer',
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
        'date',
        'responsabil_nume',
        'responsabil_functie',
        'props',
        'current_columns',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'columns',
        'visible',
        'visible_column_id',
        'status_column_id',
        'department_column_id'
    ];

    protected $with = [
        'department',
    ];

    // public function getColumnsAttribute() {

    //     if(! $this->current_columns )
    //     {
    //         return [];
    //     }

    //     $children = collect($this->current_columns)
    //         ->map(function($item) {

    //             $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();

    //             return $item;

    //         })

    //         ->filter( function($item) {
    //             return !! $item['group_id'];
    //         });


    //     $sorted = collect($this->current_columns)            
            
    //         ->push([
    //             'id' => -1,
    //             'order_no' => 999999, 
    //             'is_group' => 0, 
    //             'group_id' => NULL, 
    //             'caption' => 'a', 
    //             'type' => NULL, 
    //             'width' => NULL, 
    //             'props' => NULL,
    //         ])

    //         ->push([
    //             'id' => -2,
    //             'order_no' => -200, 
    //             'is_group' => 0, 
    //             'group_id' => NULL, 
    //             'caption' => ['Nr.', 'crt'], 
    //             'type' => 'NRCRT', 
    //             'width' => !!$this->props ? $this->props[-2] : 50, 
    //             'props' => NULL,
    //         ])

    //         ->push([
    //             'id' => -3,
    //             'order_no' => -150, 
    //             'is_group' => 0, 
    //             'group_id' => NULL, 
    //             'caption' => '', 
    //             'type' => 'CHECK', 
    //             'width' => !!$this->props ? $this->props[-3] : 50, 
    //             'props' => NULL,
    //         ])

    //         ->push([
    //             'id' => -4,
    //             'order_no' => -120, 
    //             'is_group' => 0, 
    //             'group_id' => NULL, 
    //             'caption' => 'Fișiere', 
    //             'type' => 'FILES', 
    //             'width' => !!$this->props ? $this->props[-4] : 60, 
    //             'props' => NULL,
    //         ])
            
    //         ->map(function($item) use ($children) {

    //             $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();

    //             $parent_id = $item['id'];

    //             return [
    //                 ...$item, 
    //                 'children' => $children->filter( function($child) use ($parent_id) {
    //                     return $child['group_id'] == $parent_id;
    //                 })->sortBy('order_no')->values()->toArray(),
    //             ];

    //         })

    //         ->filter( function($item) {
    //             return ! $item['group_id'];
    //         })
            
    //         ->sortBy('order_no');

    //     return $sorted->values()->toArray();

    // }

    // public function getVisibleColumnIdAttribute() {
    //     return $this->GetColumnIdByType('VISIBILITY');
    // }

    // public function getStatusColumnIdAttribute() {
    //     return $this->GetColumnIdByType('STATUS');
    // }

    // public function getDepartmentColumnIdAttribute() {
    //     return $this->GetColumnIdByType('DEPARTMENT');
    // }

    // private function GetColumnIdByType($type) {

    //     if( ! $this->columns )
    //     {
    //         return NULL;
    //     }

    //     $first = collect($this->columns)->first( function($column) use ($type) {
    //         return $column['type'] == $type;
    //     });

    //     if(!! $first)
    //     {
    //         return 1 * $first['id'];
    //     }

    //     return NULL;

    // } 

    // public function getVisibleAttribute() {
    //     return [
    //         'color' => !! $this->visibility ? 'green' : 'red',
    //         'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
    //     ];
    // }

    // public function department() {
    //     return $this->belongsTo(CustomerDepartment::class, 'department_id')->select(['id', 'departament']);
    // }

    // public static function doInsert($input, $record) {
    //     $coloane = CentralizatorColoana::where('centralizator_id', $input['centralizator_id'])->get()->toArray();

    //     $input = [
    //         ...$input,
    //         'current_columns' => $coloane,
    //     ];

    //     $record = self::create($input);

    //     return $record;
    // }

    // public static function doDuplicate($input, $record) {

    //     $newrecord = $record->replicate();

    //     if(!! $input['department_id'])
    //     {
    //         $department = CustomerDepartment::CreateIfNecessary($record->customer_id, $input['customer_id'], $input['department_id']);
    //     }
    //     else
    //     {
    //         $department = NULL;
    //     }

    //     $newrecord->customer_id = $input['customer_id'];
    //     $newrecord->department_id = !! $department ? $department->id : NULL;

    //     $newrecord->visibility = $input['visibility'];
    //     $newrecord->number = $input['number'];
    //     $newrecord->date = $input['date'];
    //     $newrecord->responsabil_nume = $input['responsabil_nume'];
    //     $newrecord->responsabil_functie = $input['responsabil_functie'];

    //     $newrecord->save();

    //     $record->DuplicateRows(
    //         $newrecord->id, 
    //         array_key_exists('department_ids', $input) ? $input['department_ids'] : [], 
    //         $input['customer_id']
    //     );

    //     return $newrecord;

    // }

    // public static function doDelete($input, $record) {
    //     $record->DeleteRows();
    //     $record->delete();
    //     return $record;
    // }

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

    // protected function DuplicateRows($id, $department_ids, $new_customer_id) {

    //     $rows = CustomerCentralizatorRow::where('customer_centralizator_id', $this->id)->get();

    //     foreach($rows as $i => $row)
    //     {

    //         $department_id = !! $row->department_id ? $row->department_id : 'none';

    //         if( in_array($department_id, $department_ids) )
    //         {
    //             $newrow = $row->replicate(['files_count']);

    //             $newrow->customer_centralizator_id = $id;
    //             $newrow->save();

    //             $row->DuplicateValues($newrow->id, $new_customer_id);
    //         }
    //     }

    // }

    // public function DeleteRows() {
    //     $rows = CustomerCentralizatorRow::where('customer_centralizator_id', $this->id)->get();
    //     foreach($rows as $i => $row)
    //     {
    //         $row->DeleteValues();

    //         $row->delete();
    //     }
    // }

    // public static function getNextNumber($input) {
    //     return (new GetNextNumber($input))->Perform();
    // }

    public static function GetQuery() {

        $q = self::query();

        if(config('app.platform') == 'b2b')
        {
            $q = $q->where('visibility', 1);
        }
        
        return $q;
    }

}