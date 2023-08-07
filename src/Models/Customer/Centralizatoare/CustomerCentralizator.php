<?php

namespace MyDpo\Models\Customer\Centralizatoare;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Exportable;
use MyDpo\Traits\Importable;
use MyDpo\Performers\Customer\Centralizatoare\Centralizator\GetNextNumber;
use MyDpo\Performers\Customer\Centralizatoare\Centralizator\SaveSettings;
use MyDpo\Models\Customer\CustomerDepartment;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Livrabile\TipCentralizatorColoana;
use MyDpo\Exports\CustomerCentralizator\Exporter;
use MyDpo\Imports\CustomerCentralizator\Importer;

// use MyDpo\Performers\CustomerCentralizator\Import;
;
// use MyDpo\Performers\CustomerCentralizator\SetAccess;

class CustomerCentralizator extends Model {

    use Itemable, Actionable, Exportable, Importable;

    protected $table = 'customers-centralizatoare';

    protected $casts = [
        'props' => 'json',
        'current_columns' => 'json',
        'columns_tree' => 'json',
        'columns_items' => 'json',
        'columns_with_values' => 'json',
        'customer_id' => 'integer',
        'centralizator_id' => 'integer',
        'department_id' => 'integer',
        'visibility' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
        'nr_crt_column_id' => 'integer',
        'visibility_column_id' => 'integer',
        'status_column_id' => 'integer',
        'department_column_id' => 'integer',
        'files_column_id' => 'integer',
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
        'columns_tree',
        'columns_items',
        'columns_with_values',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by',
        'nr_crt_column_id',
        'visibility_column_id',
        'status_column_id',
        'department_column_id',
        'files_column_id'
    ];

    protected $appends = [
        'visible',
    ];

    protected $with = [
        'department',
    ];

    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    protected static function GetExporter($input) {
        return new Exporter($input); 
    }

    protected static function GetImporter($input) {
        return new Importer($input); 
    }

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id')->select(['id', 'departament']);
    }

    public static function doInsert($input, $record) {

        $tip_centralizator = TipCentralizator::find($input['centralizator_id']);
        
        $record = self::create([
            ...$input,

            'columns_tree' => $tip_centralizator->columns_tree,
            'columns_items' => $tip_centralizator->columns_items,
            'columns_with_values' => $tip_centralizator->columns_with_values,

            'nr_crt_column_id' => $tip_centralizator->has_nr_crt_column,
            'visibility_column_id' => $tip_centralizator->has_visibility_column,
            'status_column_id' => $tip_centralizator->has_status_column,
            'department_column_id' => $tip_centralizator->has_department_column,
            'files_column_id' => $tip_centralizator->has_files_column,

            'current_columns' => $tip_centralizator->columns->toArray(), 
        ]);

        return $record;
    }

    // public function SetCurrentColumns() {
    //     $this->current_columns = CentralizatorColoana::where('centralizator_id', $this->centralizator_id)->get()->toArray();
    //     $this->save();
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

    public static function doSavesettings($input) {
        return (new SaveSettings($input))->Perform();
    }

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



    public static function GetQuery() {

        $q = self::query();

        if(config('app.platform') == 'b2b')
        {
            $q = $q->where('visibility', 1);
        }
        
        return $q;
    }

    public static function getNextNumber($input) {
        return (new GetNextNumber($input))->Perform();
    }

    // public function CorrectCurrentColumns() {

    //     if(! $this->current_columns)
    //     {
    //         $this->SetCurrentColumns();
    //     }

    //     if(! $this->current_columns)
    //     {
    //         $this->current_columns = [];
    //     }

    //     $new_columns = [...$this->current_columns];

    //     foreach($this->default_columns as $i => $column)
    //     {
    //         $exists = false;
    //         foreach($new_columns as $j => $item)
    //         {
    //             if($item['id'] == $column['id'])
    //             {
    //                 $exists = true;
    //             }
    //         }
    //         if(! $exists )
    //         {
    //             $new_columns[] = $column;
    //         }
    //     }

    //     $this->current_columns = $new_columns;
    //     $this->save();
    // }


}