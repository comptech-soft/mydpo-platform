<?php

namespace MyDpo\Exports\Customer\Centralizator;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;

use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Customer\Registre\Registru;
use MyDpo\Models\Customer\Registre\Row;

use Illuminate\Contracts\View\View;

class Exporter implements FromView, WithStrictNullComparison, ShouldAutoSize {

    public $input = NULL;

    /**
     * Departamentele ce se exporta
     */
    public $department_ids = NULL;

    /**
     * Departamentele
     */
    public $departamente = [];
    public $just_structure = NULL;

    /**
     * Id-ul registrului
     */
    public $id = NULL;
    public $document = NULL;

    protected $myclasses = [
        'centralizatoare' => [
            'document' => Centralizator::class,
            'row' => Row::class,
        ],
    ];

    public function __construct($input) {
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
        return view('exports.customer.centralizatorable.export', [
            'document' => $this->document->toArray(),
            'header' => $this->GetHeader(),
            'rows' => $this->just_structure == 1 ? [] : $this->GetRows(),
            'columns' => $this->just_structure == 1 ? [] : $this->GetColumns(),
        ]);
    }

    protected function GetRows(){
        $rows = $this->myclasses['centralizatoare']['row']::where('customer_centralizator_id', $this->input['document_id'])
            ->orderBy('order_no')
            ->get()
            ->map(function($row) {
                return [
                    'visibility' => $row->visibility,
                    'status' => $row->status,
                    'department' => $row->department->departament,
                    'values' => $this->GetValues($row),
                ];
            })
            ->toArray();

        return $rows;
    }

    protected function GetValue($record) {

        $value = $record['value'];

        if($record['type'] == 'O')
        {
            $options = collect(collect($this->document->columns_with_values)->first(function($column) use ($record){
                return $column['id'] == $record['column_id'];
            })['props'])->pluck('text', 'value')->toArray();

            return $options[$value];

        }

        if($record['type'] == 'D')
        {
            if(!! $value )
            {
                return \Carbon\Carbon::createFromFormat('Y-m-d', $value)->format('d.m.Y');
            }
        }

        if($record['type'] == 'T')
        {
            if(!! $value )
            {
                return \Carbon\Carbon::createFromFormat('Y-m-d, H:i', $value)->format('d.m.Y, H:i');
            }
        }

        return $value;
    }

    protected function GetValues($row) {

        $records = $row->props['rowvalues'];

        foreach($records as $i => $record)
        {
            $records[$i]['value'] = $this->GetValue($record);
        }
        
        return $records;
    }

    protected function GetColumns() {

        return collect($this->document->columns_with_values)
            ->filter( function($column) {
                return ! in_array($column['type'], ['NRCRT', 'VISIBILITY', 'STATUS', 'DEPARTMENT', 'CHECK', 'FILES', 'EMPTY']);
            })
            ->toArray();
    }

    protected function GetHeader() {
        /**
         * Nu vom transmite coloanele CHECK, FILES
         */

        return collect($this->document->table_headers)
            ->filter( function($column) {
                return ! in_array($column['type'], ['CHECK', 'FILES', 'EMPTY']);
            })
            ->toArray();
    }

    protected function GetDocument() {
        return $this->myclasses['centralizatoare']['document']::find($this->input['document_id']);
    }

}