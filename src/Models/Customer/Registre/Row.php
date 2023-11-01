<?php

namespace MyDpo\Models\Customer\Registre;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Customer\Centralizatoare\Rowable;
use MyDpo\Traits\Exportable;

use MyDpo\Models\Livrabile\Registre\TipRegistru;
use MyDpo\Models\Livrabile\Registre\TipRegistruColoana;

class Row extends Model {

    use Itemable, Actionable, Rowable, Exportable;

    protected $table = 'customers-registers-rows';

    protected $casts = [
        'props' => 'json',
        'register_id' => 'integer',
        'customer_id' => 'integer',
        'visibility' => 'integer',
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
        'visibility',
        'tooltip',
    ];

    protected $appends = [
        'human_status',
    ];

    protected $with = [
        'department',
    ];
    
    protected $withCount = [
        'files'
    ];

    protected static $myclasses = [
        'tip' => TipRegistru::class,
        'tipcoloana' => TipRegistruColoana::class,
        'document' => Registru::class,
        'row' => Row::class,
        'rowvalue' => RowValue::class,
        'access' => Access::class,
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
                'register_id' => $input['tip_id'],
                'customer_register_id' => $input['document_id'],
            ];
        }

        return $input;
    }

    /**
     * Duplicarea valorilor unui rand
     *      $id         = row_id pentru vechiurile randuri
     *      
     */
    public function DuplicateValues($id){
        /**
         * Pentru toate randurile
         */
        foreach(RowValue::where('row_id', $this->id)->get() as $i => $rowvalue)
        {
            RowValue::create([
                ...$rowvalue->toArray(),
                'id' => NULL,
                'row_id' => $id,
            ]);
        }
    }

}