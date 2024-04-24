<?php

namespace MyDpo\Models\Customer\Registre;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Customer\Centralizatoare\Rowable;
use MyDpo\Traits\Exportable;
use MyDpo\Traits\Importable;

use MyDpo\Models\Livrabile\Registre\TipRegistru;
use MyDpo\Models\Livrabile\Registre\TipRegistruColoana;

use MyDpo\Exports\Customer\Livrabile\Centralizatorable\Exporter;
use MyDpo\Imports\Customer\Registru\Importer;

class Row extends Model {

    use Itemable, Actionable, Rowable, Exportable, Importable;

    protected $table = 'customers-registers-rows';

    protected $casts = [
        'props' => 'json',
        'tooltip' => 'json',
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

    public static function PrepareQueryInput($input) {

       

        /**
         * Pe platforma B2B
         */
        if( config('app.platform') == 'b2b')
        {
            /**
             * Doar cele vizibile
             */
            $filter = [
                ...$input['initialfilter'],
                "(`customers-registers-rows`.`visibility` = 1)"
            ];

            /**
             * Daca stim customerul
             */
            if(array_key_exists('customer_id', $input) && !! $input['customer_id'] )
            {
                $user = \Auth::user();
                /**
                 * Daca stim rolul userului
                 */
                if($user->role)
                {
                    /**
                     * Daca este user trebuie doar cele la care avem access
                     */
                    if($user->role->id == 5)
                    {
                        $customer_registru = Registru::find($input['customer_register_id']);
                        $access = Access::where('customer_registru_id', $input['customer_register_id'])->first();

                        if(!! 1 * $customer_registru->department_column_id)
                        {
                            /** Cazul in care avem coloana departament */
                            if( ! $access->departamente || count($access->departamente) == 0) 
                            {
                                $filter[] = "(`customers-registers-rows`.`visibility` = -1)";
                            }
                            else
                            {
                                $filter[] = "(`customers-registers-rows`.`department_id` IN (" . implode(',', $access->departamente) . "))";
                            }
                        }
                        else
                        {
                            /** Cazul in care avem NU coloana departament */
                            if(! $access)
                            {                               
                                $filter[] = '(1 = 0)';
                            }
                        }
                    }
                }
            }

            $input['initialfilter'] = $filter;
        }

        

        return $input;
    }

}