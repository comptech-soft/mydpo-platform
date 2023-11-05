<?php

namespace MyDpo\Exports\Customer\Registru;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;
use MyDpo\Models\CustomerRegister;
use MyDpo\Models\Customer\Departments\Department;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {

    public $registru = NULL;
    public $juststructure = 1;
    public $departamente_ids = NULL;

    public function __construct($input) {

        dd($input);
        $this->input = $input;   
        
        $this->department_ids = array_key_exists('department_ids', $input) ? $input['department_ids'] : [];
        $this->id = $input['document_id'];
        $this->just_structure = $input['structure'];

        if(!! $this->department_ids)
        {
            $this->departamente = Department::whereIn('id', $this->department_ids)->pluck('departament', 'id')->toArray();
        }
        
        $this->document = $this->GetDocument();
    }

    public function view(): View {

        if($this->juststructure == 1)
        {
            $records = [];
        }
        else
        {
            $index = NULL;
            foreach($this->registru->columns as $i => $column)
            {
                if($column['type'] == 'DEPARTAMENT')
                {
                    $index = $i;
                }
            }

            if(! $index )
            {
                $records = $this->registru->records;
            }
            else
            {

                $departamente = $this->departamente;
                $records = collect($this->registru->records)->filter( function($item) use ($departamente, $index){

                    if(! array_key_exists($index, $item) )
                    {
                        return TRUE;
                    }
                    
                    return in_array($item[$index], $departamente);

                })->toArray();
            }
        }

        return view('exports.customer-register.export', [
            'columns' => $this->registru->columns,
            'records' => $records,
            'children' => $this->registru->children_columns,
        ]);
    }

}