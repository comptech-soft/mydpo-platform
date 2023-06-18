<?php

namespace MyDpo\Exports\Translation;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\System\Translation;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize 
{

    public $input = NULL;

    public function __construct($input) 
    {
        $this->input = $input;    
    }

    public function view(): View 
    {
        $records = ( ($this->input['structure'] == 1) ? [] : Translation::orderBy('ro')->get());

        return view('exports.admin.translations.export', [
            'records' => $records,
        ]);
    }

}