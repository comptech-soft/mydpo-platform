<?php

namespace MyDpo\Exports\CustomerCentralizator;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\CustomerCentralizator;
use MyDpo\Models\CustomerDepartment;

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


        return view('exports.customer-centralizator.export', [
            'columns' => $columns->toArray(),
            'children_columns' => $children_columns->toArray(),
            // 'children' => $this->registru->children_columns,
        ]);
    }

}