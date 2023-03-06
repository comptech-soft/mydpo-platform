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

    public function __construct($id) {

        $this->registru = CustomerRegister::where('id', $id)->first();

        dd($this->registru);
        
    }

    public function view(): View {
        return view('exports.customer-register.export', [

        ]);
    }

}
