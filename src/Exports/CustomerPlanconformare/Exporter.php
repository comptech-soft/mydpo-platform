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

        $plan->CalculateTree();

        $year = $plan->year;

        $columns = collect($plan->columns)->map( function($column) use ($year) {

            return [
                ...$column,
                'caption' => collect($column['caption'])->map( function($text) use ($year){

                    $r = \Str::replace(':year', $year, $text);

                    $parts = \Str::of($r)->explode("\r\n");

                    return $parts->join('<br/>');

                })->toArray(), 
            ];

        })->toArray();

        $records = collect($plan->GetRowsAsTable())->map(function( $item) {

            $html_style = NULL;

            if($item['type'] == 'capitol')
            {
                $html_style = 'color: #3f51b5; font-size: 14px; font-weight:bold; word-wrap: break-word;';
            }

            if($item['type'] == 'actiune')
            {
                $html_style = 'color: #1976d2; font-size: 12px; font-weight:bold; word-wrap: break-word;';
            }

            if($item['type'] == 'subactiune')
            {
                $html_style = 'color: #2196f3; font-size: 10px; font-weight:bold; word-wrap: break-word;';
            }
            
            return [
                ...$item,
                'html_style' => $html_style,
            ];

        })->toArray();

        return view('exports.customer.plan-conformare.export', [
            'plan' => $plan,
            'records' => $records,
            'columns' => $columns,
        ]);
    }

}