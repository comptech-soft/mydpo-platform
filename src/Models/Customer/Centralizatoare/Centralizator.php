<?php

namespace MyDpo\Models\Customer\Centralizatoare;

use Illuminate\Database\Eloquent\Model;

use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\Numberable;
use MyDpo\Traits\Customer\Centralizatoare\Centralizatorable;
use MyDpo\Models\Livrabile\Centralizatoare\TipCentralizator;
use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Centralizatoare\Access;
// use MyDpo\Traits\Exportable;
// use MyDpo\Traits\Importable;

// 

// use MyDpo\Exports\CustomerCentralizator\Exporter;
// use MyDpo\Imports\CustomerCentralizator\Importer;

// use MyDpo\Performers\CustomerCentralizator\Import;

class Centralizator extends Model {

    use Itemable, Actionable, Numberable, Centralizatorable; //, Exportable, Importable;

    protected $table = 'customers-centralizatoare';

    protected $casts = [
        'props' => 'json',
        'rows_counter' => 'json',
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

    protected $withCount = [
        'rows'
    ];

    public $numberable = [
        'field' => 'number',
        'where' => "(customer_id = %%customer_id%%) AND (centralizator_id = %%tip_id%%)",
        'replacement' => [
            '%%customer_id%%' => 'customer_id',
            '%%tip_id%%' => 'tip_id',
        ],
    ];

    function rows() {
        return $this->hasMany(Row::class, 'customer_centralizator_id');
    }

    public static function GetTip($input) {
        return TipCentralizator::find($input['centralizator_id']);
    }

    // protected static function GetExporter($input) {
    //     return new Exporter($input); 
    // }

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
        
        $action_at = \Carbon\Carbon::now();

        $tooltip = [
            'text' => 'Creat de :full_name/:role la :action_at. (:customer)',
            'values' => [
                'full_name' => \Auth::user()->full_name,
                'action_at' => $action_at->format('d.m.Y'),
                'role' => \Auth::user()->role->name,
                'customer' => config('app.platform') == 'b2b' ? \MyDpo\Models\Customer\Customer::find($new_customer_id)->name : 'Decalex', 
            ]
        ];


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
                $department_record = Department::CreateIfNecessary($old_customer_id, $new_customer_id, $old_department_id);

                /**
                 * Creez noul rand
                 */
                $newrow = Row::create([
                    ...$row->toArray(),
                    'id' => NULL,
                    'customer_centralizator_id' => $id,
                    'department_id' => $department_record->id,
                    'action_at' => $action_at,
                    'status' => 'new',
                    'tooltip' => $tooltip,
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

    public function RowsCountByUser($customer_id, $user) {

        if(config('app.platform') == 'b2b')
        {
            $cnt = $this->rows()->where('visibility', 1)->count();
        }
        else
        {
            $cnt = $this->rows->count();
        }
        
        if($user->role->id == 5)
        {
            if($cnt > 0)
            {
                $number_of_rows = $this->CountRows($customer_id, $user);
            }
            else
            {
                $number_of_rows = $cnt;
            }
        }
        else
        {
            $number_of_rows = $cnt;
        }

        $rows_counter = !! $this->rows_counter ? $this->rows_counter : [];
        $rows_counter = [
            ...$rows_counter,
            $user->id => $number_of_rows,
        ];

        $this->rows_counter = $rows_counter;
        $this->save();

    }

    public function CountRows($customer_id, $user) {

        $rows = ! $this->visibility_column_id ? $this->rows : $this->rows()->where('visibility', 1)->get();

        $access = Access::where('user_id', $user->id)->where('customer_id', $customer_id)->where('customer_centralizator_id', $this->id)->first();

        if( ! $access )
        {
            return 0;
        }

        $departamente = !! $access->departamente ? $access->departamente : [];
        
        $cnt = 0;

        foreach($rows as $i => $row)
        {
            if(in_array($row->department_id, $departamente))
            {
                $cnt++;
            }
        }

        return $cnt;
    }

}
