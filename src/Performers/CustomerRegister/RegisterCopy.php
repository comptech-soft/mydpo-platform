<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerRegister;
use MyDpo\Models\CustomerRegistruRow;
use MyDpo\Models\CustomerRegistruRowValue;

class RegisterCopy extends Perform {

    public function Action() {

        $targetRegister = CustomerRegister::find($this->input['register_id']);

        $input = [
            'responsabil_nume' => $this->input['responsabil_nume'],
            'responsabil_functie' => $this->input['responsabil_functie'],
            'customer_id' => $this->input['customer_id'],
            'register_id' => $targetRegister->register_id,
            'departament_id' => $this->input['departament_id'],
            'number' => $this->input['number'],
            'date' => $this->input['date'],
            'status' => $this->input['status'],
            'props' => $targetRegister->props,
            'columns' => $targetRegister->columns,
        ];

        $createdRegister = CustomerRegister::create($input);

        $targetRows = CustomerRegistruRow::where('customer_register_id', $targetRegister->id)->get();

        foreach($targetRows as $i => $targetRow)
        {

            $values = $targetRow->values()->get();

            $toCopy = false;
            foreach($values as $j => $value)
            {
                if($value['type'] == 'DEPARTAMENT')
                {
                    
                    if( in_array($value->value, $this->input['selectedDepartamente']) )
                    {
                        $toCopy = true;
                    }
                }
            }

            if($toCopy)
            {

                $createdRow = $targetRow->replicate();
                $createdRow->customer_register_id = $createdRegister->id;
                $createdRow->save();
                foreach($values as $j => $value)
                {
                    $createValue = $value->replicate();
                    $createValue->row_id = $createdRow->id;
                    $createValue->save();
                }
            }

        }

    }
    
}