<?php

namespace MyDpo\Models\Customer\Registre;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Numberable;
use MyDpo\Traits\Customer\Centralizatoare\Centralizatorable;

use MyDpo\Models\Livrabile\TipRegistru;

class Registru extends Model {

    use Itemable, Actionable, Numberable, Centralizatorable;

    protected $table = 'customers-registers';

    protected $casts = [
        'props' => 'json',
        'columns' => 'json',
        'register_id' => 'integer',
        'customer_id' => 'integer',
        'visibility' => 'integer',
        'department_id' => 'integer',
        'deleted' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'current_columns' => 'json',
        'columns_tree' => 'json',
        'columns_items' => 'json',
        'columns_with_values' => 'json',
        'nr_crt_column_id' => 'integer',
        'visibility_column_id' => 'integer',
        'status_column_id' => 'integer',
        'department_column_id' => 'integer',
        'files_column_id' => 'integer',
    ];

    protected $fillable = [
        'id',
        'responsabil_nume',
        'responsabil_functie',
        'customer_id',
        'department_id',
        'register_id',
        'columns',
        'current_columns',
        'columns_tree',
        'columns_items',
        'columns_with_values',
        'number',
        'date',
        'status',
        'visibility',
        'props',
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

    public $numberable = [
        'field' => 'number',
        'where' => "(customer_id = %%customer_id%%) AND (register_id = %%tip_id%%)",
        'replacement' => [
            '%%customer_id%%' => 'customer_id',
            '%%tip_id%%' => 'tip_id',
        ],
    ];
    
    public static function GetTip($input) {
        return TipRegistru::find($input['register_id']);
    }
    
}