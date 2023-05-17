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

        dd($this->centralizator->columns);
    }

    public function view(): View {

        

        return view('exports.customer-centralizator.export', [
            // 'columns' => $this->registru->columns,
            // 'records' => $records,
            // 'children' => $this->registru->children_columns,
        ]);
    }

}