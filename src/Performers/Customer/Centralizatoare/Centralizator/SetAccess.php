<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Centralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Models\CustomerCentralizatorAccess;

class SetAccess extends Perform {

    public function Action() {

        CustomerCentralizatorAccess::where('customer_centralizator_id', $this->customer_centralizator_id)->delete();

        if($this->departments)
        {
            $users = [];
            foreach($this->departments as $i => $item)
            {
                $parts = explode('#', $item);

                $user_id = $parts[0];
                $department_id = $parts[1];

                if( ! array_key_exists($user_id, $users) )
                {
                    $users[$user_id] = [];
                }

                $users[$user_id][] = $department_id;
            }

            foreach($users as $user_id => $departamente)
            {
                CustomerCentralizatorAccess::create([
                    'customer_centralizator_id' => $this->customer_centralizator_id,
                    'customer_id' => $this->customer_id,
                    'centralizator_id' => $this->centralizator_id,
                    'user_id' => $user_id,
                    'departamente' => $departamente,                    
                ]);
            }
        }

        $this->payload = [
            'record' => NULL,
        ];
    
    }
}