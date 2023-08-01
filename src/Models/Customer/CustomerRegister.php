<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\NextNumber;
// use MyDpo\Performers\CustomerRegister\ExportRegister;
// use MyDpo\Performers\CustomerRegister\ImportRegister;
// use MyDpo\Performers\CustomerRegister\RegisterSaveAccess;
// use MyDpo\Performers\CustomerRegister\RegisterCopy;

class CustomerRegister extends Model {

    use Itemable, Actionable, NextNumber;

    protected $table = 'customers-registers';

    protected $casts = [
        'props' => 'json',
        'columns' => 'json',
        'register_id' => 'integer',
        'customer_id' => 'integer',
        'visibility' => 'integer',
        'departament_id' => 'integer',
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
        'departament_id',
        'register_id',
        'columns',
        'number',
        'date',
        'status',
        'visibility',
        'props',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // protected $appends = [
    //     'children_columns',
    //     'real_columns',
    //     // 'records',
    // ];

    public $nextNumberColumn = 'number';

    // public function getChildrenColumnsAttribute() {
    //     return collect($this->columns)->filter( function($item) {

    //         if($item['column_type'] != 'group')
    //         {
    //             return FALSE;
    //         }
    //         if( ! array_key_exists('children', $item) )
    //         {
    //             return FALSE;
    //         }

    //         if( count($item['children']) == 0)
    //         {
    //             return FALSE;
    //         }

    //         return TRUE;

    //     })->toArray();
    // }

    // public function getRealColumnsAttribute() {
    //     $r = [];

    //     $columns = collect($this->columns)->sortBy('order_no')->toArray();

    //     foreach($columns as $i => $column)
    //     {
    //         if($column['column_type'] == 'single')
    //         {
    //             $r[$column['id']] = [
    //                 ...$column,
    //                 'value' => NULL,
    //             ];
    //         }
    //         else
    //         {
    //             if( array_key_exists('children', $column) && count($column['children']) )
    //             {
    //                 $children = collect($column['children'])->sortBy('order_no')->toArray();
    //                 foreach($children as $i => $child)
    //                 {
    //                     $r[$child['id']] = [
    //                         ...$child,
    //                         'value' => NULL,
    //                     ];
    //                 }
    //             }
    //         }
    //     }

    //     return $r;
    // }

    // public function getRecordsAttribute() {
    //     $records = [];
    //     foreach($this->rows as $i => $row)
    //     {
    //         $records[$row->id] = $row->myvalues;
    //     }
    //     return $records;
    // }

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'departament_id');
    }

    // function rows() {
    //     return $this->hasMany(CustomerRegistruRow::class, 'customer_register_id');
    // }

    // public static function getItems($input) {
    //     $query = self::query();
    //     if(config('app.platform') == 'b2b')
    //     {
    //         $query->where('status', 'public');
    //     }
    //     return (new GetItems($input, self::query()->with(['rows.values', 'department']), __CLASS__))->Perform();
    // }

    // public static function doDelete($input, $record) {

    //     CustomerRegistruUser::where('customer_registru_id', $record->id)->delete();
        
    //     $rows = CustomerRegistruRow::where('customer_register_id', $record->id)->get();

    //     foreach($rows as $i => $row)
    //     {
    //         $row->deleteRowWithValues();
    //     }

    //     $record->delete();

    //     return $record;
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

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
            ],
        ];

        return $result;
    }

    // public static function registerDownload($input) {
    //     return (new ExportRegister($input))->Perform();
    // }

    // public static function registerUpload($input) {
    //     return (new ImportRegister($input))->Perform();
    // }

    // public static function registerSaveAccess($input) {
    //     return (new RegisterSaveAccess($input))->Perform();
    // }

    // public static function registerCopy($input) {
    //     return (new RegisterCopy($input))->Perform();
    // }

    public static function nextNumberWhere($input) {
        return "(customer_id = " . $input['customer_id'] . ") AND (register_id = " . $input['register_id'] . ")";
    }

}