<?php

namespace MyDpo\Exports\CustomerPlanconformare;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\Customer\CustomerPlanconformare;

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

        $plan = CustomerPlanconformare::find($this->input['plan_id']);

        return view('exports.customer.plan-conformare.export', [
            'plan' => $plan,
            'records' => $plan->GetRowsAsTable(),
        ]);
    }

}