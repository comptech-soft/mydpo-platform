<?php

namespace MyDpo\Models\Customer;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Performers\CustomerCentralizator\GetNextNumber;
use MyDpo\Models\Livrabile\CentralizatorColoana;
use MyDpo\Performers\CustomerCentralizator\Export;

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
        'columns_tree',
        'columns_list',
        'visible',
        'visibility_column_id',
        'status_column_id',
        'department_column_id'
    ];

    protected $with = [
        'department',
    ];

    protected $default_columns = [
        [
            'id' => 'empty',
            'order_no' => 999999, 
            'is_group' => 0, 
            'group_id' => NULL, 
            'caption' => 'a', 
            'type' => 'EMPTY', 
            'width' => NULL, 
            'props' => NULL,
        ],

        [
            'id' => 'nr_crt',
            'order_no' => -200, 
            'is_group' => 0, 
            'group_id' => NULL, 
            'caption' => ['Nr.', 'crt'], 
            'type' => 'NRCRT', 
            'width' => 50, 
            'props' => NULL,
        ],

        [
            'id' => 'check',
            'order_no' => -150, 
            'is_group' => 0, 
            'group_id' => NULL, 
            'caption' => '', 
            'type' => 'CHECK', 
            'width' => 50, 
            'props' => NULL,
        ],

        [
            'id' => 'files',
            'order_no' => -120, 
            'is_group' => 0, 
            'group_id' => NULL, 
            'caption' => 'Fișiere', 
            'type' => 'FILES', 
            'width' => 60, 
            'props' => NULL,
        ]
    ];

    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    public function getColumnsTreeAttribute() {

        $this->CorrectCurrentColumns();

        $columns = collect($this->current_columns)
            ->filter(function($column){
                return ! $column['group_id'];
            })
            ->map(function($item) {
                $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();
                return [
                    ...$item,
                    'children' => [],
                ];
            })
            ->sortBy('order_no')
            ->values()
            ->toArray();

        foreach($columns as $i => $column)
        {
            $columns[$i]['children'] = self::CreateColumnChildren($column, $this->current_columns);
        }

        return $columns;
    }

    public function getColumnsListAttribute() {
        $list = [];

        foreach($this->columns_tree as $i => $node)
        {
            if( count($node['children']) == 0)
            {
                $list[] = $node;
            }

            foreach($node['children'] as $j => $child)
            {
                $list[] = $child;
            }
        }

        return $list;
    }

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id')->select(['id', 'departament']);
    }

    public function getVisibilityColumnIdAttribute() {
        return $this->GetColumnIdByType('VISIBILITY');
    }

    public function getStatusColumnIdAttribute() {
        return $this->GetColumnIdByType('STATUS');
    }

    public function getDepartmentColumnIdAttribute() {
        return $this->GetColumnIdByType('DEPARTMENT');
    }

    private function GetColumnIdByType($type) {

        if( ! $this->current_columns )
        {
            return NULL;
        }

        $first = collect($this->current_columns)->first( function($column) use ($type) {
            return $column['type'] == $type;
        });

        if(!! $first)
        {
            return 1 * $first['id'];
        }

        return NULL;

    } 

    public static function doInsert($input, $record) {

        $record = self::create([
            ...$input,
            'current_columns' => CentralizatorColoana::where('centralizator_id', $input['centralizator_id'])->get()->toArray(),
        ]);

        return $record;

    }

    public function SetCurrentColumns() {
        $this->current_columns = CentralizatorColoana::where('centralizator_id', $this->centralizator_id)->get()->toArray();
        $this->save();
    }

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

    public static function doExport($input) {
        return (new Export($input))->Perform();
    }

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

    public function CorrectCurrentColumns() {

        if(! $this->current_columns)
        {
            $this->SetCurrentColumns();
        }

        if(! $this->current_columns)
        {
            $this->current_columns = [];
        }

        $new_columns = [...$this->current_columns];

        foreach($this->default_columns as $i => $column)
        {
            $exists = false;
            foreach($new_columns as $j => $item)
            {
                if($item['id'] == $column['id'])
                {
                    $exists = true;
                }
            }
            if(! $exists )
            {
                $new_columns[] = $column;
            }
        }

        $this->current_columns = $new_columns;
        $this->save();
    }

    public static function CreateColumnChildren($column, $current_columns) {

        $children = [];

        foreach($current_columns as $i => $item)
        {
            if(!! $item['group_id'] && ($item['group_id'] == $column['id']))
            {
                $children[] = $item;
            }
        }

        return collect($children)
            ->map(function($item) {
                $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();
                return $item;
            })
            ->sortBy('order_no')
            ->values()
            ->toArray();
    }

}