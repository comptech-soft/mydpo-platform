<?php

namespace MyDpo\Exports\Customer\Entities\Account;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {

    public $input = NULL;
    public $records = [];

    public function __construct($input) {

        $this->input = $input;           
    }

    public function view(): View  {
        $records = ( ($this->input['structure'] == 1) ? [] : []);

        return view('exports.customer.entities.accounts.export', [
            'records' => $records,
        ]);
    }

}