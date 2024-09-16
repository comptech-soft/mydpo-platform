<?php

namespace MyDpo\Exports\Livrabile\Chestionare;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\Livrabile\Chestionare\Chestionar;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input, $record = NULL) 
    {
        $this->input = $input;
    
        $this->record = $record;    
    }

    public function view(): View 
    {

        $questions = Chestionar::PrepareSourceToDuplicate($this->record, []);
        dd($questions);

        return view('exports.livrabile.chestionar.export', [
            'chestionar' => $this->record->toArray(),
        ]);
    }

}