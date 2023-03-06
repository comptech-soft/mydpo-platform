<?php

namespace MyDpo\Imports\CustomerRegister;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\CustomerRegister;
use MyDpo\Models\CustomerRegistruRow;
use MyDpo\Models\CustomerRegistruRowValue;
use MyDpo\Models\CustomerDepartment;

class Importer implements ToCollection {

    public $input = NULL;
    public $registru = NULL;
    public $departamente = NULL;

    public function __construct($input) {
        $this->input = $input;
        $this->registru = CustomerRegister::where('id', $this->input['id'])->first();

        $this->departamente = CustomerDepartment::where('customer_id', $this->registru->customer_id)->pluck('id', 'departament')->toArray();
    }

    public function collection(Collection $rows) {
        foreach($rows as $i => $row)
        {
            if($i > 1)
            {
                $this->processRow($row);
            }
        }
    }

    private function processRow($row) {

        $rowinput = [
            'customer_register_id' => $this->registru->id,
            'customer_id' => $this->registru->customer_id,
            'register_id' => $this->registru->props['registru']['id'],
            'status' => 'protected',
        ];

        $rowrecord = CustomerRegistruRow::create($rowinput);

        $i = 0;
        foreach($this->registru->real_columns as $column_id => $column)
        {
            $valueinput = [
                'row_id' => $rowrecord->id,
                'column_id' => $column_id,
                'value' => $row[$i],
            ];

            if($column['type'] == 'departament')
            {
                $valueinput = [
                    ...$valueinput,
                    'value' => $this->departamente[$valueinput['value']],
                ];
                
            }
            else
            {
                if($column['type'] == 'O')
                {
                    $options = collect($column['props']['options'])->pluck('value', 'text');

                    $valueinput = [
                        ...$valueinput,
                        'value' => $options[$valueinput['value']],
                    ];
                }
            }

            CustomerRegistruRowValue::create($valueinput);

            $i++;
        }

    }    

}