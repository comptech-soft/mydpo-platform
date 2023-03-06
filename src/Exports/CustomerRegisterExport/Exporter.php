<?php

namespace MyDpo\Exports\CustomerRegisterExport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\CustomerRegister;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {

    public $registru = NULL;
    public $juststructure = 1;

    public function __construct($id, $juststructure) {

        $this->juststructure = $juststructure;

        $this->registru = CustomerRegister::where('id', $id)->first();
    }

    public function view(): View {
        return view('exports.customer-register.export', [
            'columns' => $this->registru->columns,
            'records' => $this->juststructure == 1 ? [] : $this->registru->records,
            'children' => $this->registru->children_columns,
        ]);
    }

}

