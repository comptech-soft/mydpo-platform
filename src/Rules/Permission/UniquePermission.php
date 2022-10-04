<?php

namespace MyDpo\Rules\Permission;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Permission;

class UniquePermission implements Rule {

    public $input = NULL;
    public $record = NULL;

    public function __construct($input) {
        $this->input = $input;
    }

    public function passes($attribute, $value) {   

        if( $this->input['parent_id'] )
        {
            $q = Permission::where('name', $this->input['name'])
                ->where('parent_id', $this->input['parent_id']); 
        }
        else
        {
            $q = Permission::where('name', $this->input['name']); 
        }


        if(array_key_exists('id', $this->input) && $this->input['id'])
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();

        if($this->record)
        {
            return FALSE;
        }
        
        return TRUE;
    }

    public function message() {
        return 'Permisiunea este deja definită.';
    }
}