<?php

namespace MyDpo\Models\Customer\Centralizatoare;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Customer\Centralizatoare\Rowable;
use MyDpo\Traits\Exportable;
use MyDpo\Traits\Importable;

use MyDpo\Models\Livrabile\Centralizatoare\TipCentralizator;
use MyDpo\Models\Livrabile\Centralizatoare\TipCentralizatorColoana;

use MyDpo\Exports\Customer\Centralizatorable\Exporter;
use MyDpo\Imports\Customer\Centralizator\Importer;

class Row extends Model {

    use Itemable, Actionable, Rowable, Exportable, Importable;

    protected $table = 'customers-centralizatoare-rows';

    protected $casts = [
        'customer_centralizator_id' => 'integer',
        'customer_id' => 'integer',
        'centralizator_id' => 'integer',
        'department_id' => 'integer',
        'order_no' => 'integer',
        'deleted' => 'integer',
        'visibility' => 'integer',
        'props' => 'json',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_centralizator_id',
        'customer_id',
        'centralizator_id',
        'department_id',
        'order_no',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by',
        'visibility',
        'status',
        'action_at',
        'tooltip',
    ];

    protected $appends = [
        'human_status',
    ];

    protected $with = [
        'department',
    ];

    protected $withCount = [
        'files',
    ];

    protected static $myclasses = [
        'tip' => TipCentralizator::class,
        'tipcoloana' => TipCentralizatorColoana::class,
        'document' => Centralizator::class,
        'row' => Row::class,
        'rowvalue' => RowValue::class,
        'access' => Access::class,
    ];
    
    protected static function GetExporter($input) {
        return new Exporter($input); 
    }

    protected static function GetImporter($input) {
        return new Importer($input); 
    }
    
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
                'centralizator_id' => $input['tip_id'],
                'customer_centralizator_id' => $input['document_id'],
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