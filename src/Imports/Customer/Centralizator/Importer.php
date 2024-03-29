<?php

namespace MyDpo\Imports\Customer\Centralizator;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Carbon\Carbon;

use MyDpo\Models\Customer\Customer;
use MyDpo\Models\Customer\Departments\Department;

use MyDpo\Models\Customer\Centralizatoare\Centralizator;
use MyDpo\Models\Customer\Centralizatoare\Row;
use MyDpo\Models\Customer\Centralizatoare\RowValue;

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

        $this->departamente = Department::where('customer_id', $this->input['customer_id'])->pluck('id', 'departament')->toArray();

        $this->document = $this->myclasses[$this->input['model']]['document']::where('id', $this->input['document_id'])->first();

        $this->columns = $this->GetColumns();
    }

    public function collection(Collection $rows) {
        $this->CreateLines($rows);
        $this->Process();
    }

    private function RowToRecord($row) {

        $record = [
            'rowvalues' => [],
        ];

        $i = 0;

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
            $value = $row[$i++];
			
            if( ($column['type'] == 'O') && !! $value)
			{
				$first = collect($column['props'])->where('text', $value)->first();
				
				if(!! $first)
				{
					$value = $first['value'];
				}
			}
			
			if( ($column['type'] == 'D') && !! $value )
			{
				if (strpos($value, '.') !== false) 
				{
					$value = \Carbon\Carbon::createFromFormat('d.m.Y', $value)->format('Y-m-d');
				}
			}
			
			if( ($column['type'] == 'T') && !! $value )
			{
				$parts = explode(',', $value);
				$d = trim($parts[0]);
				$t = trim($parts[1]);
				
				if (strpos($d, '.') !== false) 
				{
					$d = \Carbon\Carbon::createFromFormat('d.m.Y', $d)->format('Y-m-d');
					
					$value = $d . ', ' . $t;
				}
			}

            $record['rowvalues']['col-' . $column['id']] = [
                'id' => NULL,
                'row_id' => NULL,
                'column_id' => $column['id'],
                'value' => $value,
                'type' => $column['type'],
                'column' => $column['type'],
            ]; 
        }

        return $record;
    }

    private function ValidRecord($record) {

        $r = FALSE;

        foreach($record['rowvalues'] as $col => $item)
        {
            if(!! $item['value'])
            {
                $r = TRUE;
            }
        }

        if( array_key_exists('visibility', $record))
        {
            if( in_array($record['visibility'], [0, 1]) )
            {
                $r = TRUE;
            }
        }

        if( array_key_exists('status', $record))
        {
            if( !! $record['status'] )
            {
                $r = TRUE;
            }
        }

        if( array_key_exists('department_id', $record))
        {
            if( !! $record['department_id'] )
            {
                $r = TRUE;
            }
        }

        return $r;
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

        $action_at = \Carbon\Carbon::now();

        $tooltip = [
            'text' => 'Creat de :full_name/:role la :action_at. (:customer)',
            'values' => [
                'full_name' => \Auth::user()->full_name,
                'action_at' => $action_at->format('d.m.Y'),
                'role' => \Auth::user()->role->name,
                'customer' => config('app.platform') == 'b2b' ? $this->customer->name : 'Decalex',
            ]
        ];

        $department_id = NULL;

        if(array_key_exists('department_id', $line))
        {
            if(!! $line['department_id'])
            {
                if(array_key_exists($line['department_id'], $this->departamente))
                {
                    $department_id = $this->departamente[$line['department_id']];
                }
                else
                {
                    $department = Department::CreateIfNotExists($this->input['customer_id'], trim($line['department_id']));

                    $department_id = $department->id;
                }
            }
        }

        $input = [
            $this->myclasses[$this->input['model']]['fk_col'] => $this->document->id,
            $this->myclasses[$this->input['model']]['tip_col'] => $this->input['tip_id'],
            'customer_id' => $this->input['customer_id'],
            'order_no' => 1 + $this->myclasses[$this->input['model']]['row']::where($this->myclasses[$this->input['model']]['fk_col'], $this->document->id)->count(),
            'action_at' => Carbon::now()->format('Y-m-d'),
            'tooltip' => $tooltip,
            'visibility' => array_key_exists('visibility', $line) ? $line['visibility'] : 0,
            'status' => array_key_exists('status', $line) ? preg_replace('/[\x00-\x1F\x7F\xC2\xA0]/', '', $line['status']) : NULL,
            'department_id' => $department_id,
        ];
        
        try
        {
            $row = $this->myclasses[$this->input['model']]['row']::create($input);

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
        }
        catch(\Exception $e)
        {
        }

    }

    protected function GetColumns() {

        return collect($this->document->columns_with_values)
            ->filter( function($column) {
                return ! in_array($column['type'], ['NRCRT', 'VISIBILITY', 'STATUS', 'DEPARTMENT', 'CHECK', 'FILES', 'EMPTY']);
            })
            ->toArray();
    }
}