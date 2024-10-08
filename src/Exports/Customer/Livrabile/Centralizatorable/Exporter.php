<?php

namespace MyDpo\Exports\Customer\Livrabile\Centralizatorable;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromView;

use MyDpo\Models\Customer\Departments\Department;

use MyDpo\Models\Customer\Centralizatoare\Centralizator;
use MyDpo\Models\Customer\Centralizatoare\Row as CentralizatorRow;

use MyDpo\Models\Customer\Registre\Registru;
use MyDpo\Models\Customer\Registre\Row as RegistruRow;

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
            'row' => CentralizatorRow::class,
            'field' => 'customer_centralizator_id',
        ],

        'registre' => [
            'document' => Registru::class,
            'row' => RegistruRow::class,
            'field' => 'customer_register_id',
        ],
    ];

    public function __construct($input, $record = NULL) {
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
        $department_ids = $this->department_ids;

        $rows = $this->myclasses[$this->input['model']]['row']::where($this->myclasses[$this->input['model']]['field'], $this->input['document_id'])
            ->orderBy('order_no')
            ->get()
            ->map(function($row) {
                return [
                    'visibility' => $row->visibility,
                    'status' => $row->status,
                    'department_id' => $row->department_id,
                    'department' => $row->department->departament,
                    'values' => $this->GetValues($row),
                ];
            })
            ->filter(function($row) use($department_ids) {
                return in_array($row['department_id'], $department_ids);
            })
            ->toArray();

        return $rows;
    }

    protected function GetValue($record) {

        $value = $record['value'];

        try
        {
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
        }
        catch(\Exception $e)
        {

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
         * Nu vom transmite coloanele NRTCRT, CHECK, FILES, EMPTY
         */
        $ommited_columns = ($this->just_structure == 1 ? ['NRCRT', 'CHECK', 'FILES', 'EMPTY'] : ['CHECK', 'FILES', 'EMPTY']);

        return collect($this->document->table_headers)
            ->filter( function($column) use ($ommited_columns){
                return ! in_array($column['type'], $ommited_columns);
            })
            ->toArray();
    }

    protected function GetDocument() {
        return $this->myclasses[$this->input['model']]['document']::find($this->input['document_id']);
    }

}