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

    // public $registru = NULL;
    // public $juststructure = 1;
    // public $departamente_ids = NULL;

    public function __construct() {

        
    }

    public function view(): View {
        return view('exports.admin.translations.export', [
            'records' => Translation::orderBy('ro')->get(),
        ]);
    }

}