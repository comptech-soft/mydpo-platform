<?php

namespace MyDpo\Exports\CustomerRegisterExport;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {
    
    public function __construct() {
        dd(__METHOD__);
    }

    public function view(): View {
        return view('aaaa', [

        ]);
    }

}
