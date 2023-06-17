<?php

namespace MyDpo\Exports\Translation;

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

       
    }

    public function view(): View {

        dd(__METHOD__);
        return view('exports.customer-register.export', [
            'columns' => $this->registru->columns,
            'records' => $records,
            'children' => $this->registru->children_columns,
        ]);
    }

}