<?php

namespace MyDpo\Exports\CustomerCentralizator;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\CustomerCentralizator;
use MyDpo\Models\CustomerDepartment;
use MyDpo\Models\CustomerCentralizatorRow;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {

    /**
     * Departamentele ce se exporta
     */
    public $department_ids = NULL;

    /**
     * Departamentele
     */
    public $departamente = [];
    // public $juststructure = 1;

    /**
     * Id-ul registrului
     */
    public $id = NULL;
    public $centralizator = NULL;

    public function __construct($department_ids, $id) {

        // $this->juststructure = $juststructure;
        $this->department_ids = $department_ids;
        $this->id = $id;


        if(!! $this->department_ids)
        {
            $this->departamente = CustomerDepartment::whereIn('id', $this->department_ids)
                ->pluck('departament', 'id')
                ->toArray();
        }

        $this->centralizator = CustomerCentralizator::where('id', $this->id)->first();

    }

    public function view(): View {

        
        $columns = collect($this->centralizator->columns)->filter(function($item){

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

        $children_columns = collect($this->centralizator->columns)->filter(function($item){

            return count($item['children']) > 0;
        });

        $has_children = $children_columns->count() > 0;

        $columns = $columns->map(function($item) use ($has_children){
            return [
                ...$item,
                'colspan' => count($item['children']) == 0 ? NULL : count($item['children']) ,
                'rowspan' => count($item['children']) > 0 ? NULL : ($has_children ? 2 : NULL),
            ];
        });

        $rows = CustomerCentralizatorRow::where('customer_centralizator_id', $this->id)->get();

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

        // dd($value_columns);

        $records = [];
        foreach($rows as $i => $row)
        {
            $item = [];

            // dd($row->rowvalues);

            foreach($value_columns as $j => $column)
            {
          
                $rowvalue = $row->rowvalues->where('column_id', $column['id'])->first();

                if(! $rowvalue )
                {
                    $item[] = NULL;
                }
                else
                {
                    if($column['type'] == 'DEPARTMENT')
                    {
                        if(array_key_exists($rowvalue->value, $this->departamente))
                        {
                            $item[] = $this->departamente[$rowvalue->value];
                        }
                        else
                        {
                            $item[] = NULL;
                        }
                    }
                    else
                    {
                        if($column['type'] == 'O')
                        {
                            $options = collect($column['props'])->pluck('text', 'value')->toArray();

                            if(array_key_exists($rowvalue->value, $options))
                            {
                                $item[] = $options[$rowvalue->value];
                            }
                            else
                            {
                                $item[] = NULL;
                            }
                        }
                        else
                        {
                            $item[] = $rowvalue->value;
                        }
                    }
                }
                    
            }

            $records[] = $item;
        }



        return view('exports.customer-centralizator.export', [
            'columns' => $columns->toArray(),
            'children_columns' => $children_columns->toArray(),
            'records' => $records,
        ]);
    }

}