<?php

namespace MyDpo\Exports\Livrabile\Chestionare;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\System\Translation;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input, $record = NULL) {
        $this->input = $input;
        $this->record = $record;    
    }

    public function view(): View {

        // dd(__METHOD__);
        // $records = ( ($this->input['structure'] == 1) ? [] : Translation::orderBy('ro')->get());

        return view('exports.livrabile.chestionar.export', [
            'records' => [],
        ]);
    }

}