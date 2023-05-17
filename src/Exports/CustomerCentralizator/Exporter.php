<?php

namespace MyDpo\Exports\CustomerCentralizator;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
// use MyDpo\Models\CustomerRegister;
// use MyDpo\Models\CustomerDepartment;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {

    // public $registru = NULL;
    // public $juststructure = 1;
    // public $departamente_ids = NULL;

    public function __construct() {

        // $this->juststructure = $juststructure;
        // $this->departamente_ids = $departamente_ids;

        // $this->departamente = CustomerDepartment::whereIn('id', $departamente_ids)
        //     ->select('departament')
        //     ->get()
        //     ->map( function($item) {return $item->departament; })
        //     ->toArray();

        // $this->registru = CustomerRegister::where('id', $id)->first();
    }

    public function view(): View {

        

        return view('exports.customer-centralizator.export', [
            // 'columns' => $this->registru->columns,
            // 'records' => $records,
            // 'children' => $this->registru->children_columns,
        ]);
    }

}