<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerRegister;
use MyDpo\Models\CustomerRegistruRow;
use MyDpo\Models\CustomerRegistruRowValue;
use MyDpo\Models\CustomerDepartment;

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
                        $departament_id = $this->CreateDrpartament($value->value, $this->input['customer_id']);
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
                    if($value['type'] == 'DEPARTAMENT')
                    {
                        $createValue->value = $departament_id;
                    }

                    $createValue->save();
                }
            }

        }

        $this->payload = [
            'record' => $createdRegister,
        ];

    }

    public function CreateDrpartament($departament_id, $customer_id) {

        $targetDep = CustomerDepartment::find($departament_id);

        $exists = CustomerDepartment::where('departament', $targetDep->departament)->where('customer_id', $customer_id)->first();

        if( ! $exists )
        {
            $exists = CustomerDepartment::create([
                'departament' => $targetDep->departament,
                'customer_id' => $customer_id
            ]);
        }

        return $exists->id;

    }
    
}