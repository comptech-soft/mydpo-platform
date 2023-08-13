<?php

namespace MyDpo\Models\Customer\Centralizatoare;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Numberable;
use MyDpo\Traits\Customer\Centralizatoare\Centralizatorable;

use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Customer\CustomerDepartment;
use MyDpo\Models\Customer;
// 
// use MyDpo\Traits\Exportable;
// use MyDpo\Traits\Importable;

// 

// use MyDpo\Models\Livrabile\TipCentralizatorColoana;
// use MyDpo\Exports\CustomerCentralizator\Exporter;
// use MyDpo\Imports\CustomerCentralizator\Importer;

// use MyDpo\Performers\CustomerCentralizator\Import;

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

    /**
     * Duplicarea unui centralizator
     */
    public static function Duplicate($input) {
        /**
         * Documentul duplicat
         */
        $source = self::find($input['document_id']);

        /**
         * Destinatia. Se salveaza informatiile completate in interfata
         */
        $dest = self::create([
            ...$source->toArray(),
            'id' => NULL,
            'customer_id' => $input['customer_id'],
            'department_id' => $input['department_id'],
            'visibility' => $input['visibility'],
            'number' => $input['number'],
            'date' => $input['date'],
            'responsabil_nume' => $input['responsabil_nume'],
            'responsabil_functie' => $input['responsabil_functie']
        ]);

        /**
         * Se duplica randurile documentului de duplicat
         */
        $source->DuplicateRows(
            $dest->id, 
            array_key_exists('department_ids', $input) ? $input['department_ids'] : [], 
            $input['customer_id'],
            $source->customer_id
        );

        return $dest;
    }
    
    /**
     * Duplicarea randurilor
     *      $id                 = id-ul documentului duplicat
     *      $department_ids     = departamentele ce trebuie duplicate
     *      $new_customer_id    = noul client
     *      $old_customer_id    = vechiul client
     */
    protected function DuplicateRows($id, $department_ids, $new_customer_id, $old_customer_id) {

        /**
         * Randurile vechiului document, pentru fiecare rand
         */
        foreach(Row::where('customer_centralizator_id', $this->id)->get() as $i => $row)
        {
            /**
             * Alu id-ul departamentului din randul vechi
             */
            $old_department_id = $row->department_id;

            if( !! $old_department_id && in_array($old_department_id, $department_ids) )
            {
                /**
                 * Creez departamentul
                 */
                $department_record = CustomerDepartment::CreateIfNecessary($old_customer_id, $new_customer_id, $old_department_id);

                /**
                 * Creez noul rand
                 */
                $newrow = Row::create([
                    ...$row->toArray(),
                    'id' => NULL,
                    'customer_centralizator_id' => $id,
                    'department_id' => $department_record->id,
                    'action_at' => ($action_at = \Carbon\Carbon::now()),
                    'status' => 'new',
                    'tooltip' => __('Creat de :full_name la :action_at. (:customer)', [
                        'action_at' => $action_at->format('d.m.Y'),
                        'full_name' => \Auth::user()->full_name,
                        'customer' => Customer::find($new_customer_id)->name,
                    ]),
                ]);

                /**
                 * Se duplica valorile randului
                 */
                $row->DuplicateValues($newrow->id);
            }
        }

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
