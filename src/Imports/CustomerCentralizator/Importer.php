<?php

namespace MyDpo\Imports\CustomerCentralizator;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;
use MyDpo\Models\CustomerCentralizator;
use MyDpo\Models\CustomerDepartment;
use MyDpo\Models\Customer;
use MyDpo\Models\CustomerCentralizatorRow;
use MyDpo\Models\CustomerCentralizatorRowValue;

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


    public function __construct($input) {

        $this->input = $input;

        $this->centralizator = CustomerCentralizator::where('id', $this->input['id'])->first();

        $this->departamente = CustomerDepartment::where('customer_id', $this->centralizator->customer_id)->pluck('id', 'departament')->toArray();

        $this->customer = Customer::find($this->centralizator->customer_id);
    }

    public function collection(Collection $rows) {

        $value_columns = $this->value_columns($this->columns());
        $start_row = $this->has_children_header() ? 3 : 2;
        $rows_count = CustomerCentralizatorRow::where('customer_centralizator_id', $this->input['id'])->count();

        foreach($rows as $i => $row)
        {
            if($i >= $start_row)
            {
                $this->processRow($value_columns, $row, $rows_count - $start_row + $i + 1);
            }
        }
    }

    private function processRow($value_columns, $row, $order_no) {
        
        $input = [
            'customer_centralizator_id' => $this->input['id'],
            'customer_id' => $this->centralizator->customer_id,
            'centralizator_id' => $this->centralizator->centralizator_id,
            'order_no' => $order_no,
            'props' => [
                'action' => [
                    'name' => 'import',
                    'action_at' => Carbon::now()->format('Y-m-d'),
                    'tooltip' => 'Importat de :user_full_name la :action_at. (:customer_name)',
                    'user' => [
                        'id' => \Auth::user()->id,
                        'full_name' => \Auth::user()->full_name,
                        'role' => [
                            'name' => \Auth::user()->role->name,
                        ]
                    ],
                    'customer' => [
                        'id' => $this->centralizator->customer_id,
                        'name' => $this->customer->name,
                    ],
                ],
                
            ],
        ];

        $rowrecord = CustomerCentralizatorRow::create($input);

        $this->AttachRowValues($rowrecord, $value_columns, $row->toArray());
    
    }    

    protected function AttachRowValues($rowrecord, $value_columns, $rows) {

        foreach($value_columns as $i => $column)
        {
            $this->AttachRowValue($rowrecord, $column, $rows[$i]);
            
        }
    }

    protected function AttachRowValue($rowrecord, $column, $row) {

        $input = [
            'row_id' => $rowrecord->id,
            'column_id' => $column['id'],
        ];

        $method = 'value' . ucfirst(strtolower($column['type']));

        $input['value'] = $this->{$method}($row, $column);

        CustomerCentralizatorRowValue::create($input);

    }   

    protected function valueVisibility($value, $column) {
        return $value;
    }

    protected function valueStatus($value, $column) {
        return $value;
    }

    protected function valueDepartment($value, $column) {

        if( array_key_exists($value, $this->departamente) )
        {
            return $this->departamente[$value];
        }
        
        return NULL;
    }

    protected function valueO($value, $column) {

        $options = collect($column['props'])->pluck('value', 'text')->toArray();

        if( array_key_exists($value, $options) )
        {
            return $options[$value];
        }

        
        return NULL;
    }

    
    

    protected function valueN($value, $column) {
        return $value;
    }

    protected function valueC($value, $column) {
        return $value;
    }

    protected function valueD($value, $column) {
        return $value;
    }

    protected function valueT($value, $column) {
        return $value;
    }

    protected function columns() {
        return collect($this->centralizator->columns)->filter(function($item){

            if( in_array($item['type'], ['NRCRT', 'CHECK', 'FILES']) )
            {
                return false;
            }

            if( ! $item['type']  )
            {
                return count($item['children']) > 0;
            }

            return true;
        });
    }

    protected function value_columns($columns) {

        $value_columns = [];
        
        foreach($columns as $i => $column)
        {
            if( count($column['children']) == 0)
            {
                $value_columns[] = $column;
            }
            else
            {
                foreach( $column['children'] as $j => $child)
                {
                    $value_columns[] = $child;
                }
            }
        }

        return $value_columns;
    }

    protected function has_children_header() {
        $r = FALSE;

        foreach($this->centralizator->columns as $i => $column)
        {
            if( !! count($column['children']) )
            {
                $r = TRUE;
            }
        }
        return $r;
    }

}