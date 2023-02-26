<?php

namespace MyDpo\Performers\CustomerRegistruAsociat;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerRegistruAsociat;

class SaveAsociere extends Perform {

    public function Action() {

        dd($this->input);

        CustomerRegistruAsociat::where('customer_id', $this->input['customer_id'])->delete();

        foreach($this->input['registre'] as $i => $register_id)
        {
            CustomerRegistruAsociat::create([
                'register_id' => $register_id,
                'customer_id' => $this->input['customer_id'],
            ]);
        }
    
    }
}