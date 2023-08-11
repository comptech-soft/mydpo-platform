<?php

namespace MyDpo\Models\Customer\Registre;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Actionable;

use MyDpo\Traits\Customer\Centralizatoare\Rowable;
// use MyDpo\Helpers\Performers\Datatable\DoAction;
// use MyDpo\Performers\CustomerRegistruRow\ChangeStatus;
// use MyDpo\Performers\CustomerRegistruRow\ChangeStare;
// use MyDpo\Performers\CustomerRegistruRow\DeleteRows;
// use MyDpo\Performers\CustomerRegistruRow\UploadFile;
// use MyDpo\Performers\CustomerRegistruRow\LoadFiles;
// use MyDpo\Performers\CustomerRegistruRow\DeleteFile;

class Row extends Model {

    use Actionable, Rowable;

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
        'department_id',
        'order_no',
        'status',
        'props',
        'deleted',
        'customer',
        'createdby',
        'created_by',
        'updated_by',
        'deleted_by',
        'action_at',
        'tooltip',
    ];

    // protected $appends = [
    //     'myvalues',
    // ];

    // protected $withCount = ['files'];

    // public function registru() {
    //     return $this->belongsTo(CustomerRegister::class, 'customer_register_id');
    // }

    // public function values() {
    //     return $this->hasMany(CustomerRegistruRowValue::class, 'row_id');
    // }

    // public function files() {
    //     return $this->hasMany(CustomerRegistruRowFile::class, 'row_id');
    // }

    // public function getMyvaluesAttribute() {
    //     $r = [...$this->registru->real_columns];

    //     $values = $this->values()->pluck('value', 'column_id')->toArray();

    //     $departamente = CustomerDepartment::where('customer_id', $this->registru->customer_id)->pluck('departament', 'id')->toArray();

    //     foreach($r as $i => $item)
    //     {
    //         $r[$i]['value'] = array_key_exists($item['id'], $values) ? $values[$item['id']] : NULL;

    
    //         if($item['type'] == 'DEPARTAMENT')
    //         {
    //             if($r[$i]['value'])
    //             {
    //                 $r[$i]['value'] = $departamente[$r[$i]['value']];
    //             }
    //         }
    //         else
    //         {
    //             if($item['type'] == 'O')
    //             {
    //                 $options = collect($item['props']['options'])->pluck('text', 'value')->toArray();
    //                 if($r[$i]['value'])
    //                 {
    //                     $r[$i]['value'] = $options[$r[$i]['value']];
    //                 }
    //             }
    //         }
    //     }

    //     return collect($r)->map(function($item){
    //         return $item['value'];
    //     })->toArray();
    // }
    
    // public static function doDelete($input, $record) {
    //     if($record)
    //     {
    //         $record->values()->delete();
    //         $record->delete();
    //     }
    //     return $record;
    // }

    // public function deleteRowWithValues() {
    //     CustomerRegistruRowValue::where('row_id', $this->id)->delete();
    //     $this->delete();
    // }

    // public static function doUpdate($input, $record) {
    //     $record->update([
    //         'customer_register_id' => $input['customer_register_id'],
    //         'customer_id' => $input['customer_id'],
    //         'register_id' => $input['register_id'],
    //         'createdby' => \Auth::user()->full_name,
    //         'customer' => config('app.platform') == 'b2b' ? Customer::find($input['customer_id'])->name : 'Decalex',
    //         'props' => [
    //             'rowvalues' => $input['rowvalues'],
    //         ],
            
    //     ]);

    //     if(array_key_exists('rowvalues', $input))
    //     {
    //         foreach($input['rowvalues'] as $key => $value)
    //         {
    //             $column_id = \Str::replace('col-', '', $key);
    //             $rowvalue = CustomerRegistruRowValue::where('column_id', $column_id)->where('row_id', $record->id)->first();

    //             if($rowvalue)
    //             {
    //                 if($rowvalue->type == 'STARE')
    //                 {
    //                     $value = 'edited';
    //                 }
    //                 $rowvalue->update(['value' => $value]);
    //             }
    //         }
    //     }
    //     return $record;
    // }

    // public static function doInsert($input, $record) {

    //     $record = self::create([
    //         'customer_register_id' => $input['customer_register_id'],
    //         'customer_id' => $input['customer_id'],
    //         'register_id' => $input['register_id'],
    //         'departament_id' => $input['departament_id'],
    //         'order_no' => $input['order_no'],
    //         'status' => $input['status'],
    //         'createdby' => \Auth::user()->full_name,
    //         'created_by' => \Auth::user()->id,
    //         'customer' => config('app.platform') == 'b2b' ? Customer::find($input['customer_id'])->name : 'Decalex',
    //         'props' => [
    //             'rowvalues' => $input['rowvalues'],
    //         ],
            
    //     ]);

    //     if(array_key_exists('rowvalues', $input))
    //     {
    //         foreach($input['rowvalues'] as $key => $value)
    //         {
    //             $column_id =  \Str::replace('col-', '', $key);

    //             $column = RegistruColoana::find($column_id);

    //             CustomerRegistruRowValue::create([
    //                 'row_id' => $record->id,
    //                 'column_id' => \Str::replace('col-', '', $key),
    //                 'value' => $column->type == 'STARE' ? 'new' : $value,
    //                 'type' => $column->type,
    //             ]);
    //         }
    //     }

    //     return $record;
    // } 

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function changeStatus($input) {
    //     return (new ChangeStatus($input))->Perform();
    // }

    // public static function changeStare($input) {
    //     return (new ChangeStare($input))->Perform();
    // }

    // public static function deleteRows($input) {
    //     return (new DeleteRows($input))->Perform();
    // }

    // public static function uploadFile($input) {
    //     return (new UploadFile($input))->Perform();
    // }

    // public static function loadFiles($input) {
    //     return (new LoadFiles($input))->Perform();
    // }

    // public static function deleteFile($input) {
    //     return (new DeleteFile($input))->Perform();
    // }

    public static function PrepareActionInput($action, $input) {

        if( in_array($action, ['insert']) )
        {
            $input = [
                ...$input,
                'register_id' => $input['tip_id'],
                'customer_register_id' => $input['document_id'],
            ];
        }

        return $input;
    }

}