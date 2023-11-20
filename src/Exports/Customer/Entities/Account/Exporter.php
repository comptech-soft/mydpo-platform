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
    public $html_styles = [];

    public function __construct($input) {

        dd($input);
        $this->input = $input;   
        $this->plan = Planconformare::find($this->input['plan_id']); 
        $this->plan->CalculateTree();

        $this->html_styles = [
            'capitol' => [
                'color' => '#3f51b5', 
                'font-size' => '14px', 
                'word-wrap' => 'break-word',
            ],

            'actiune' => [
                'color' => '#1976d2', 
                'font-size' => '12px', 
                'word-wrap' => 'break-word',
            ],

            'subactiune' => [
                'color' => '#2196f3', 
                'font-size' => '10px', 
                'word-wrap' => 'break-word',
            ],

            'modalitate' => [
                'color' => '#000000', 
                'font-size' => '9px', 
                'word-wrap' => 'break-word',
            ],
        ];
    }

    public function view(): View {
        $nodes = PlanconformareRow::where('customer_plan_id', $this->plan->id)->whereNull('parent_id')->orderBy('order_no')->get();

        $this->records = [];

        $this->GetRecords($nodes, '', 1);

        return view('exports.customer.plan-conformare.export', [
            'plan' => $this->plan,
            'records' => $this->ToRecords($this->records),
            'columns' => $this->GetColumns(),
        ]);
    }

    protected function ToRecords($records) {

        $html_styles = $this->html_styles;
        
        $records = collect($records)->map(function($record) use ($html_styles) {

            $style = '';
            foreach($html_styles[$record['type']] as $i => $item)
            {
                $style .= ($i . ':' . $item . ';');
            }

            return [
                ...$record,
                'html_style' => $style,
            ];
        })->toArray();

        return $records;
    }

    protected function GetRecords($nodes, $prefix, $level) {
        
        foreach($nodes as $i => $node)
        {
            $this->records[] = [
                ...$node->toArray(),
                'prefix' => ($p = ($prefix . ($i + 1)) . '. '),
                'level' => $level,
            ];

            $this->GetRecords($node->children, $p, $level + 1);
        }
    }

    protected function GetColumns() {

        $year = $this->plan->year;

        $columns = collect($this->plan->columns)->filter(function($column) {
            return $column['id'] != 2;
        })->map( function($column) use ($year) {

            return [
                ...$column,
                'caption' => collect($column['caption'])->map( function($text) use ($year){

                    $r = \Str::replace(':year', $year, $text);

                    $parts = \Str::of($r)->explode("\r\n");

                    return $parts->join('<br/>');

                })->toArray(), 
            ];

        })->toArray();

        return $columns;
    }

}