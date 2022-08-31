<?php

namespace MyDpo\Rules\CustomerFolder;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\CustomerFolder;

class ValidName implements Rule {

    public $input = NULL;
    public $folder = NULL;

    public function __construct($input)
    {
        $this->input = $input;
    }

    public function passes($attribute, $value)
    {   
        if( $this->input['parent_id'] )
        {
            $q = CustomerFolder::where('customer_id', $this->input['customer_id'])
                ->where('name', $this->input['name'])
                ->where('parent_id', $this->input['parent_id']); 
        }
        else
        {
            $q = CustomerFolder::where('customer_id', $this->input['customer_id'])
                ->where('name', $this->input['name']); 
        }


        if(array_key_exists('id', $this->input) && $this->input['id'])
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->folder = $q->first();

        if($this->folder)
        {
            return FALSE;
        }
        
        return TRUE;
    }

    public function message()
    {
        return 'Folderul "' . $this->input['name'] . '" deja există.';
    }
}
