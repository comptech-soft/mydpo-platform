<?php

namespace MyDpo\Rules\Customer\Livrabile\Documentsable\Folders;

use Illuminate\Contracts\Validation\Rule;
use MyDpo\Models\Customer\Documents\Folder;

class UniqueName implements Rule {

    public $input = NULL;
    public $action = NULL;
    public $record = NULL;

    public function __construct($action, $input) {
        $this->input = $input;
        $this->action = $action;
    }

    public function passes($attribute, $value) {   

        $q = Folder::where('customer_id', $this->input['customer_id'])
            ->where('name', $this->input['name'])
            ->where('type', $this->input['type']);

        if(!! $this->input['parent_id'] )
        {
            $q->where('parent_id', $this->input['parent_id']); 
        }
        else
        {
            $q->whereNull('parent_id');
        }    
        if($this->action == 'update')
        {
            $q->where('id', '<>', $this->input['id']);
        }

        $this->record = $q->first();

        return ! $this->record;
    }

    public function message() {
        return 'Folderul ' . $this->input['name'] . ' există';
    }
}
