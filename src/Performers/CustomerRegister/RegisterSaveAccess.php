<?php

namespace MyDpo\Performers\CustomerRegister;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerRegistruUser;

class RegisterSaveAccess extends Perform {

    public function Action() {

        CustomerRegistruUser::where('customer_registru_id', $this->input['customer_registru_id'])->delete();

        if(array_key_exists('selected', $this->input))
        {
            foreach($this->input['selected'] as $user_id => $departamente)
            {
                CustomerRegistruUser::create([
                    'customer_registru_id' => $this->input['customer_registru_id'],
                    'customer_id' => $this->input['customer_id'],
                    'register_id' => $this->input['register_id'],
                    'user_id' => $user_id,
                    'departamente' => $departamente,                    
                ]);
            }
        }
    }
    
}