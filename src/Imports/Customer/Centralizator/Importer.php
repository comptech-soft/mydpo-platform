<?php

namespace MyDpo\Imports\Customer\Centralizator;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use MyDpo\Models\Customer\CustomerCentralizator;
use MyDpo\Models\Customer\CustomerDepartment;
use MyDpo\Models\Customer;
use MyDpo\Models\Customer\CustomerCentralizatorRow;
use MyDpo\Models\Customer\CustomerCentralizatorRowValue;

class Importer implements ToCollection {

    public $input = NULL;

    /**
     * centralizatorul in care se importa randuri
     */
    public $centralizator = NULL;
    
    /**
     * departamentele clientului
     */
    public $departamente = NULL;

    /**
     * customerul
     */
    public $customer = NULL;

    public $columns_ids = [];


    public function __construct($input) {
        $this->input = $input;

        $this->centralizator = CustomerCentralizator::where('id', $this->input['id'])->first();
        $this->departamente = CustomerDepartment::where('customer_id', $this->centralizator->customer_id)->pluck('id', 'departament')->toArray();
        $this->customer = Customer::find($this->centralizator->customer_id);

        $this->columns_ids = $this->List();
    }

    public function collection(Collection $rows) {

        $this->CreateLines($rows);

        $this->Process();


        // $value_columns = $this->value_columns($this->columns());
        // $start_row = $this->has_children_header() ? 2 : 1;
        // $rows_count = CustomerCentralizatorRow::where('customer_centralizator_id', $this->input['id'])->count();

        // foreach($rows as $i => $row)
        // {
        //     if($i >= $start_row)
        //     {
        //         $this->processRow($value_columns, $row, $rows_count - $start_row + $i + 1);
        //     }
        // }
    }

    private function RowToRecord($row) {

        $record = [];

        foreach($row as $i => $field)
        {
            if($i > 0)
            {
                $record[] = [
                    'column_id' => $this->columns_ids[$i]['column_id'],
                    'column' => $this->columns_ids[$i]['type'],
                    'value' => $field,
                ];

            }
        }

        return $record;
    }

    private function ValidRecord($record) {
        return TRUE;
    }

    protected function CreateLines($rows) {
        /**
         * Din input se ia start_row = linia de inceput
         */
        $start_row = 1 * $this->input['start_row'];

        $this->lines = $rows->filter( function($row, $i) use ($start_row) {
            /**
             * Se incepe de la linia $start_row
             */
            return $i >= $start_row; 
        
        })->map( function($row, $i) {

            /**
             * Se convertesc randurile la tabela din BD
             */
            return $this->RowToRecord($row);
        
        })->filter( function($record, $i) {

            /**
             * Logica de validare a recordurilor
             */
            return $this->ValidRecord($record);

        })->values()->toArray();
    }

    protected function Process() {
        foreach($this->lines as $i => $line) 
        {
            $this->ProcessLine($line);
        }
    }

    private function ProcessLine($line) {

        if(config('app.platform') == 'admin')
		{
			$role = \Auth::user()->role;
		}
		else
		{
			$role = \Auth::user()->roles()->where('customer_id', $this->centralizator->customer_id)->first();
		}

        $rowrecord = CustomerCentralizatorRow::create([
            'customer_centralizator_id' => $this->input['id'],
            'customer_id' => $this->centralizator->customer_id,
            'centralizator_id' => $this->centralizator->centralizator_id,
            'order_no' => NULL,
            'props' => [
                'action' => [
                    'name' => 'import',
                    'action_at' => Carbon::now()->format('Y-m-d'),
                    'tooltip' => 'Importat de :user_full_name la :action_at. (:customer_name)',
                    'user' => [
                        'id' => \Auth::user()->id,
                        'full_name' => \Auth::user()->full_name,
                        'role' => [
                            'id' => $role ? $role->id : NULL,
							'name' => $role ? $role->name : NULL,
                        ]
                    ],
                    'customer' => [
                        'id' => $this->centralizator->customer_id,
                        'name' => $this->customer->name,
                    ],
                ],
            ],
        ]);

        foreach($line as $i => $rowvalue)
        {
            if($rowvalue['column'] == 'DEPARTMENT')
            {
                if( array_key_exists($rowvalue['value'], $this->departamente) )
                {
                    $rowvalue['value'] = $this->departamente[$rowvalue['value']];
                }
                else
                {
                    $departament = CustomerDepartment::create([
                        'customer_id' => $this->centralizator->customer_id,
                        'departament' => $rowvalue['value'],
                    ]);

                    $this->departamente = CustomerDepartment::where('customer_id', $this->centralizator->customer_id)->pluck('id', 'departament')->toArray();

                    $rowvalue['value'] = $departament->id;
                }
            }

            CustomerCentralizatorRowValue::create([...$rowvalue, 'row_id' => $rowrecord->id]);
        }




    }

    // private function processRow($value_columns, $row, $order_no) {
        

		


    //     

    //     $this->AttachRowValues($rowrecord, $value_columns, $row->toArray());
    
    // }      

    // protected function AttachRowValues($rowrecord, $value_columns, $rows) {

    //     foreach($value_columns as $i => $column)
    //     {
    //         $this->AttachRowValue($rowrecord, $column, $rows[$i]);
            
    //     }
    // }

    // protected function AttachRowValue($rowrecord, $column, $row) {

    //     $input = [
    //         'row_id' => $rowrecord->id,
    //         'column_id' => $column['id'],
    //     ];

    //     $method = 'value' . ucfirst(strtolower($column['type']));

    //     $input['value'] = $this->{$method}($row, $column);

    //     

    // }   

    // protected function valueVisibility($value, $column) {
    //     return $value;
    // }

    // protected function valueStatus($value, $column) {
    //     return $value;
    // }

    // protected function valueDepartment($value, $column) {


        
    //     return NULL;
    // }

    // protected function valueO($value, $column) {

    //     $options = collect($column['props'])->pluck('value', 'text')->toArray();

    //     if( array_key_exists($value, $options) )
    //     {
    //         return $options[$value];
    //     }

    //     return NULL;
    // }

    // protected function valueN($value, $column) {
    //     return $value;
    // }

    // protected function valueC($value, $column) {
    //     return $value;
    // }

    // protected function valueD($value, $column) {
    //     return $value;
    // }

    // protected function valueT($value, $column) {
    //     return $value;
    // }

    // protected function columns() {
    //     return collect($this->centralizator->columns)->filter(function($item){

    //         if( in_array($item['type'], ['NRCRT', 'CHECK', 'FILES']) )
    //         {
    //             return false;
    //         }

    //         if( ! $item['type']  )
    //         {
    //             return count($item['children']) > 0;
    //         }

    //         return true;
    //     });
    // }

    // protected function value_columns($columns) {

    //     $value_columns = [];
        
    //     foreach($columns as $i => $column)
    //     {
    //         if( count($column['children']) == 0)
    //         {
    //             $value_columns[] = $column;
    //         }
    //         else
    //         {
    //             foreach( $column['children'] as $j => $child)
    //             {
    //                 $value_columns[] = $child;
    //             }
    //         }
    //     }

    //     return $value_columns;
    // }

    // protected function has_children_header() {
    //     $r = FALSE;

    //     foreach($this->centralizator->columns as $i => $column)
    //     {
    //         if( !! count($column['children']) )
    //         {
    //             $r = TRUE;
    //         }
    //     }
    //     return $r;
    // }

    protected function Columns() {
        return collect($this->centralizator->columns_tree)->filter( function($item) {
            return ! in_array($item['type'], ['CHECK', 'FILES', 'EMPTY']);
        })->map(function($item){

            $caption = $item['caption'];

            if(is_string($caption))
            {
                $caption = \Str::replace('#', ' ', $caption);
            }
            else
            {
                if(is_array($caption))
                {
                    $caption = implode(' ', $caption);
                }
            }

            $type = $item['type'];

            if( ! $type )
            {
                $type = 'group';
            }

            return [
                ...$item,
                'caption' => $caption,
                'type' => $type,
            ];

        })->toArray();
    }

    protected function Children() {
        return collect($this->Columns())->filter(function($item){
            return count($item['children']) > 0;
        });
    }

    protected function List() {
        $list = [];

        foreach($this->Columns() as $i => $column)
        {
            if( count($column['children']) == 0)
            {
                $list[] = [
                    'column_id' => $column['id'],
                    'type' => $column['type'],
                ];
            }
            else
            {
                foreach($column['children'] as $j => $child)
                {
                    $list[] = [
                        'column_id' => $child['id'],
                        'type' => $child['type'],
                    ];
                }
            }
        }

        return $list;
    }

}