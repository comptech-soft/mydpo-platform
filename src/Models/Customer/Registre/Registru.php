<?php

namespace MyDpo\Models\Customer\Registre;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Numberable;

use MyDpo\Models\Customer\CustomerDepartment;
use MyDpo\Models\Livrabile\TipRegistru;

class Registru extends Model {

    use Itemable, Actionable, Numberable;

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
    ];

    protected $fillable = [
        'id',
        'responsabil_nume',
        'responsabil_functie',
        'customer_id',
        'department_id',
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
    
    public function getVisibleAttribute() {
        return [
            'color' => !! $this->visibility ? 'green' : 'red',
            'icon' => !! $this->visibility ? 'mdi-check' : 'mdi-cancel',
        ];
    }

    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id')->select(['id', 'departament']);
    }

    public static function doInsert($input, $record) {

        
        $tip_registru = TipRegistru::find($input['register_id']);

       
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

    public static function GetQuery() {

        $q = self::query();

        if(config('app.platform') == 'b2b')
        {
            $q = $q->where('visibility', 1);
        }
        
        return $q;
    }

    

}