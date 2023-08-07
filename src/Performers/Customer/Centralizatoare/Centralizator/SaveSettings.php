<?php

namespace MyDpo\Performers\Customer\Centralizatoare\Centralizator;

use MyDpo\Helpers\Perform;
use MyDpo\Models\Customer\Centralizatoare\CustomerCentralizator;

class SaveSettings extends Perform {

    public function Action() {
        
        $customer_centralizator = CustomerCentralizator::find($this->id);

        $widths = collect($this->columns)->pluck('width', 'id')->toArray();

        $columns_items = collect([...$customer_centralizator->columns_items])->map(function($column) use ($widths){

            return [
                ...$column,
                'width' => array_key_exists($column['id'], $widths) ? $widths[$column['id']] : $column['width']
            ];

        })->toArray();

        $columns_tree = [...$customer_centralizator->columns_tree];


        $customer_centralizator->columns_items = $columns_items;
        $customer_centralizator->columns_tree = $columns_tree;

        $customer_centralizator->save();    
    }
}