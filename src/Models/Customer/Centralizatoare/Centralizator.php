<?php

namespace MyDpo\Models\Customer\Centralizatoare;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Numberable;
use MyDpo\Traits\Customer\Centralizatoare\Centralizatorable;

use MyDpo\Models\Livrabile\TipCentralizator;
// 
// use MyDpo\Traits\Exportable;
// use MyDpo\Traits\Importable;

// use MyDpo\Performers\Customer\Centralizatoare\Centralizator\SaveSettings;
// 

// use MyDpo\Models\Livrabile\TipCentralizatorColoana;
// use MyDpo\Exports\CustomerCentralizator\Exporter;
// use MyDpo\Imports\CustomerCentralizator\Importer;

// use MyDpo\Performers\CustomerCentralizator\Import;
;
// use MyDpo\Performers\CustomerCentralizator\SetAccess;

class Centralizator extends Model {

    use Itemable, Actionable, Numberable, Centralizatorable; //, Exportable, Importable;

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
        'table_headers',
    ];

    protected $with = [
        'department',
    ];

    public $numberable = [
        'field' => 'number',
        'where' => "(customer_id = %%customer_id%%) AND (centralizator_id = %%tip_id%%)",
        'replacement' => [
            '%%customer_id%%' => 'customer_id',
            '%%tip_id%%' => 'tip_id',
        ],
    ];

    public static function GetTip($input) {
        return TipCentralizator::find($input['centralizator_id']);
    }

    public static function Duplicate($input) {
        dd(__METHOD__, $input);
    }

    public function DeleteRows() {
        $rows = Row::where('customer_centralizator_id', $this->id)->get();
        foreach($rows as $i => $row)
        {
            $row->DeleteValues();
            $row->delete();
        }
    }

}