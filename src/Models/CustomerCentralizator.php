<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class CustomerCentralizator extends Model {

    use Itemable, Actionable;

    protected $table = 'customers-centralizatoare';

    protected $casts = [
        'props' => 'json',
        'current_columns' => 'json',
        'customer_id' => 'integer',
        'centralizator_id' => 'integer',
        'department_id' => 'integer',
        'visibility' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_id',
        'centralizator_id',
        'department_id',
        'visibility',
        'number',
        'date',
        'responsabil_nume',
        'responsabil_functie',
        'props',
        'current_columns',
        'deleted',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    protected $appends = [
        'columns',
    ];

    protected $with = [
        'department',
    ];

    public function getColumnsAttribute() {

        if(! $this->current_columns )
        {
            return [];
        }

        $children = collect($this->current_columns)
            ->map(function($item) {

                $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();

                return $item;

            })

            ->filter( function($item) {
                return !! $item['group_id'];
            });


        $sorted = collect($this->current_columns)            
            
            ->push([
                'id' => -1,
                'order_no' => 999999, 
                'is_group' => 0, 
                'group_id' => NULL, 
                'caption' => 'a', 
                'type' => NULL, 
                'width' => 2, 
                'props' => NULL,
            ])
            
            ->map(function($item) use ($children) {

                $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();

                $parent_id = $item['id'];

                return [
                    ...$item, 
                    'children' => $children->filter( function($child) use ($parent_id) {
                        return $child['group_id'] == $parent_id;
                    })->sortBy('order_no')->values()->toArray(),
                ];

            })

            ->filter( function($item) {
                return ! $item['group_id'];
            })
            
            ->sortBy('order_no');

        return $sorted->values()->toArray();

    }


    public function department() {
        return $this->belongsTo(CustomerDepartment::class, 'department_id')->select(['id', 'departament']);
    }

    public static function doInsert($input, $record) {


        $coloane = CentralizatorColoana::where('centralizator_id', $input['centralizator_id'])->get()->toArray();

        $input = [
            ...$input,
            'current_columns' => $coloane,
        ];

        $record = self::create($input);

        return $record;



    }

}