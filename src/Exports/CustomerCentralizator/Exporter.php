<?php

namespace MyDpo\Exports\CustomerCentralizator;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\Customer\CustomerCentralizator;
use MyDpo\Models\Customer\CustomerDepartment;
use MyDpo\Models\Customer\CustomerCentralizatorRow;

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
        
        $columns = $this->columns();

        $children_columns = $this->children_columns();

        $columns = $this->columns_colspan_rowspan($columns, $children_columns->count() > 0);

        return view('exports.customer-centralizator.export', [
            'columns' => $columns->toArray(),
            'children_columns' => $children_columns->toArray(),
            'records' => $this->records($columns),
        ]);
    }

    protected function records($columns) {

        $rows = CustomerCentralizatorRow::where('customer_centralizator_id', $this->id)->get();

        $value_columns = $this->value_columns($columns);

        $records = [];

        foreach($rows as $i => $row)
        {
            
            if($this->row_department_is_selected($row))
            {
                $records[] = $this->item($value_columns, $row);
            }
        }

        return $records;
    }

    protected function row_department_is_selected($row) {
		
		if(! $this->centralizator->department_column_id)
		{
			return true;
		}
		
        if(! $this->department_ids)
        {
            return false;
        }

        $rowvalue = $row->rowvalues->where('column_id', $this->centralizator->department_column_id)->first();

        if(! $rowvalue)
        {
            return false;
        }
        
        return in_array($rowvalue->value, $this->department_ids);
    }

    protected function item($value_columns, $row) {
        $item = [];

        foreach($value_columns as $j => $column)
        {
            $rowvalue = $row->rowvalues->where('column_id', $column['id'])->first();

            $item[] = $this->value($rowvalue, $column);   
        }

        return $item;
    }

    protected function valueVisibility($rowvalue, $column) {
        return $rowvalue->value;
    }

    protected function valueStatus($rowvalue, $column) {
        return $rowvalue->value;
    }

    protected function valueDepartment($rowvalue, $column) {
        return array_key_exists($rowvalue->value, $this->departamente) ? $this->departamente[$rowvalue->value] : NULL;
    }

    protected function valueC($rowvalue, $column) {
        return $rowvalue->value;
    }

    protected function valueN($rowvalue, $column) {
        return $rowvalue->value;
    }

    protected function valueD($rowvalue, $column) {
        return $rowvalue->value;
    }

    protected function valueT($rowvalue, $column) {
        return $rowvalue->value;
    }

    protected function valueO($rowvalue, $column) {
        $options = collect($column['props'])->pluck('text', 'value')->toArray();

        return array_key_exists($rowvalue->value, $options) ? $options[$rowvalue->value] : NULL;
    }

    protected function value($rowvalue, $column) {
        if(! $rowvalue )
        {
            return NULL;
        }

        $method = 'value' . ucfirst(strtolower($column['type']));

        return $this->{$method}($rowvalue, $column);
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

    protected function columns_colspan_rowspan($columns, $has_children) {

        return $columns->map(function($item) use ($has_children){
            return [
                ...$item,
                'colspan' => count($item['children']) == 0 ? NULL : count($item['children']) ,
                'rowspan' => count($item['children']) > 0 ? NULL : ($has_children ? 2 : NULL),
            ];
        });

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

    protected function children_columns() {
        return collect($this->centralizator->columns)->filter(function($item){

            return count($item['children']) > 0;

        });
    }

}