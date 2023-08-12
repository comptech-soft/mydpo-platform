<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Centralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Livrabile\TipCentralizatorColoana;
use MyDpo\Models\Livrabile\TipCentralizator;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizator;

class SaveSettings extends Perform {

    public function Action() {
        
        $customer_centralizator = CustomerCentralizator::find($this->id);

        foreach($widths = collect($this->columns)->pluck('width', 'id')->toArray() as $column_id => $width)
        {
            $column = TipCentralizatorColoana::where('centralizator_id', $customer_centralizator->centralizator_id)
                ->where('id', $column_id)
                ->first();

            $column->width = $width;

            $column->save();
        }

        $centralizator = TipCentralizator::find($customer_centralizator->centralizator_id);

        $customer_centralizator->columns_items = $centralizator->columns_items;
        $customer_centralizator->columns_tree = $centralizator->columns_tree;
        $customer_centralizator->columns_with_values = $centralizator->columns_with_values;    
        $customer_centralizator->save(); 
           
    }
}