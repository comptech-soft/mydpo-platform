<?php

namespace MyDpo\Imports\Customer\Centralizator;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\CustomerDepartment;

use MyDpo\Models\Customer\Centralizatoare\Centralizator;
use MyDpo\Models\Customer\Centralizatoare\Row;
use MyDpo\Models\Customer\Centralizatoare\Rowvalue;

class Importer implements ToCollection {

    protected $input = NULL;
    protected $lines = NULL;
    /**
     * centralizatorul/registrul in care se importa randuri
     */
    public $document = NULL;
    
    /**
     * departamentele clientului
     */
    public $departamente = NULL;

    /**
     * customerul
     */
    public $customer = NULL;

    public $columns = [];

    public $myclasses = [
        'centralizatoare' => [
            'document' => Centralizator::class,
            'row' => Row::class,
            'rowvalue' => Rowvalue::class,
            'fk_col' => 'customer_centralizator_id',
            'tip_col' => 'centralizator_id',
        ],
    ];

    public function __construct($input) {
        $this->input = $input;

        $this->customer = Customer::find($this->input['customer_id']);

        $this->departamente = CustomerDepartment::where('customer_id', $this->input['customer_id'])->pluck('id', 'departament')->toArray();

        $this->document = $this->myclasses[$this->input['model']]['document']::where('id', $this->input['document_id'])->first();

        $this->columns = $this->GetColumns();
    }

    public function collection(Collection $rows) {
        $this->CreateLines($rows);
        $this->Process();
    }

    private function RowToRecord($row) {

        $record = [
            'nrcrt' => $row[0],
            'rowvalues' => [],
        ];

        $i = 1;

        if($this->document->visibility_column_id)
        {
            $record['visibility'] = $row[$i++];
        }
        
        if($this->document->status_column_id)
        {
            $record['status'] = $row[$i++];
        }
        
        if($this->document->department_column_id)
        {
            $record['department_id'] = $row[$i++];
        }

        foreach($this->columns as $j => $column)
        {
            $record['rowvalues']['col-' . $column['id']] = [
                'id' => NULL,
                'row_id' => NULL,
                'column_id' => $column['id'],
                'value' => $row[$i++],
                'type' => $column['type'],
                'column' => $column['type'],
            ]; 
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

        $row = $this->myclasses[$this->input['model']]['row']::create([
            $this->myclasses[$this->input['model']]['fk_col'] => $this->document->id,
            $this->myclasses[$this->input['model']]['tip_col'] => $this->input['tip_id'],
            'customer_id' => $this->input['customer_id'],
            'order_no' => 1 + $this->myclasses[$this->input['model']]['row']::where($this->myclasses[$this->input['model']]['fk_col'], $this->document->id)->count(),
            'action_at' => Carbon::now()->format('Y-m-d'),
            'tooltip' => '???',
            'visibility' => array_key_exists('visibility', $line) ? $line['visibility'] : 0,
            'status' => array_key_exists('status', $line) ? $line['status'] : NULL,
            'department_id' => array_key_exists('department_id', $line) ? $this->departamente[$line['department_id']] : NULL,
        ]);

        foreach($line['rowvalues'] as $i => $rowvalue)
        {
            $line['rowvalues'][$i]['row_id'] = $row->id;
            $value = $this->myclasses[$this->input['model']]['rowvalue']::create($line['rowvalues'][$i]);
            $line['rowvalues'][$i]['id'] = $value->id;
        }

        $row->props = [
            'rowvalues' => $line['rowvalues']
        ];
        $row->save();



        // foreach($line as $i => $rowvalue)
        // {
        //     if($rowvalue['column'] == 'DEPARTMENT')
        //     {
        //         if( array_key_exists($rowvalue['value'], $this->departamente) )
        //         {
        //             $rowvalue['value'] = $this->departamente[$rowvalue['value']];
        //         }
        //         else
        //         {
        //             $departament = CustomerDepartment::create([
        //                 'customer_id' => $this->centralizator->customer_id,
        //                 'departament' => $rowvalue['value'],
        //             ]);

        //             $this->departamente = CustomerDepartment::where('customer_id', $this->centralizator->customer_id)->pluck('id', 'departament')->toArray();

        //             $rowvalue['value'] = $departament->id;
        //         }
        //     }

        //     CustomerCentralizatorRowValue::create([...$rowvalue, 'row_id' => $rowrecord->id]);
        // }




    }

    protected function GetColumns() {

        return collect($this->document->columns_with_values)
            ->filter( function($column) {
                return ! in_array($column['type'], ['NRCRT', 'VISIBILITY', 'STATUS', 'DEPARTMENT', 'CHECK', 'FILES', 'EMPTY']);
            })
            ->toArray();
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

    // protected function Columns() {
    //     return collect($this->centralizator->columns_tree)->filter( function($item) {
    //         return ! in_array($item['type'], ['CHECK', 'FILES', 'EMPTY']);
    //     })->map(function($item){

    //         $caption = $item['caption'];

    //         if(is_string($caption))
    //         {
    //             $caption = \Str::replace('#', ' ', $caption);
    //         }
    //         else
    //         {
    //             if(is_array($caption))
    //             {
    //                 $caption = implode(' ', $caption);
    //             }
    //         }

    //         $type = $item['type'];

    //         if( ! $type )
    //         {
    //             $type = 'group';
    //         }

    //         return [
    //             ...$item,
    //             'caption' => $caption,
    //             'type' => $type,
    //         ];

    //     })->toArray();
    // }

    // protected function Children() {
    //     return collect($this->Columns())->filter(function($item){
    //         return count($item['children']) > 0;
    //     });
    // }

    // protected function List() {
    //     $list = [];

    //     foreach($this->Columns() as $i => $column)
    //     {
    //         if( count($column['children']) == 0)
    //         {
    //             $list[] = [
    //                 'column_id' => $column['id'],
    //                 'type' => $column['type'],
    //             ];
    //         }
    //         else
    //         {
    //             foreach($column['children'] as $j => $child)
    //             {
    //                 $list[] = [
    //                     'column_id' => $child['id'],
    //                     'type' => $child['type'],
    //                 ];
    //             }
    //         }
    //     }

    //     return $list;
    // }

}