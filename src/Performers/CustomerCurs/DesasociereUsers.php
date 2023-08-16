<?php

namespace MyDpo\Performers\CustomerCurs;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCursUser;
use MyDpo\Models\Customer\ELearning\CustomerCurs;
use MyDpo\Models\SharematerialDetail;

class DesasociereUsers extends Perform {

    public function Action() {

        if( array_key_exists('user_id', $this->input) )
        {
            $user = CustomerCursUser::where('customer_id', $this->input['customer_id'])
                ->where('user_id', $this->input['user_id'])
                ->where('curs_id', $this->input['curs_id'])
                ->first();

            $user->delete();

            SharematerialDetail::where('customer_id', $this->input['customer_id'])
                ->where('assigned_to', $this->input['user_id'])
                ->where('trimitere_id', $this->input['trimitere_id'])
                ->where('sended_document_id', $this->input['curs_id'])
                ->delete();

            $customercurs = CustomerCurs::where('customer_id', $this->input['customer_id'])
                ->where('curs_id', $this->input['curs_id'])
                ->first();

            if($customercurs->cursusers->count() == 0)
            {
                CustomerCurs::where('customer_id', $this->input['customer_id'])
                    ->where('curs_id', $this->input['curs_id'])
                    ->delete();   
            }
        }
        else
        {
            SharematerialDetail::where('customer_id', $this->input['customer_id'])
                ->where('sended_document_id', $this->input['curs_id'])
                ->delete();

            CustomerCursUser::where('customer_id', $this->input['customer_id'])
                ->where('curs_id', $this->input['curs_id'])
                ->delete();

            CustomerCurs::where('customer_id', $this->input['customer_id'])
                ->where('curs_id', $this->input['curs_id'])
                ->delete();   
        }
    
    }
}