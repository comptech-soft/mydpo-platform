<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;

class CustomerCentralizatorRow extends Model {

    use Itemable;

    protected $table = 'customers-centralizatoare-rows';

    protected $casts = [
        'customer_centralizator_id' => 'integer',
        'customer_id' => 'integer',
        'centralizator_id' => 'integer',
        'department_id' => 'integer',
        'order_no' => 'integer',
        'deleted' => 'integer',
        'props' => 'json',
        'created_by' => 'integer',
        'updated_by' => 'integer',
        'deleted_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'customer_centralizator_id',
        'customer_id',
        'centralizator_id',
        'department_id',
        'order_no',
        'deleted',
        'props',
        'created_by',
        'updated_by',
        'deleted_by'
    ];

    // protected $appends = [
    //     'columns',
    // ];

    protected $with = [
        'rowvalues',
    ];

    // public function getColumnsAttribute() {

    //     if(! $this->current_columns )
    //     {
    //         return [];
    //     }

    //     $children = collect($this->current_columns)
    //         ->map(function($item) {

    //             $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();

    //             return $item;

    //         })

    //         ->filter( function($item) {
    //             return !! $item['group_id'];
    //         });


    //     $sorted = collect($this->current_columns)            
            
    //         ->push([
    //             'id' => -1,
    //             'order_no' => 999999, 
    //             'is_group' => 0, 
    //             'group_id' => NULL, 
    //             'caption' => 'a', 
    //             'type' => NULL, 
    //             'width' => NULL, 
    //             'props' => NULL,
    //         ])

    //         ->push([
    //             'id' => -2,
    //             'order_no' => -200, 
    //             'is_group' => 0, 
    //             'group_id' => NULL, 
    //             'caption' => ['Nr.', 'crt'], 
    //             'type' => 'NRCRT', 
    //             'width' => 80, 
    //             'props' => NULL,
    //         ])

    //         ->push([
    //             'id' => -3,
    //             'order_no' => -150, 
    //             'is_group' => 0, 
    //             'group_id' => NULL, 
    //             'caption' => 'Selectare', 
    //             'type' => 'CHECK', 
    //             'width' => 80, 
    //             'props' => NULL,
    //         ])

    //         ->push([
    //             'id' => -4,
    //             'order_no' => -120, 
    //             'is_group' => 0, 
    //             'group_id' => NULL, 
    //             'caption' => 'Fișiere', 
    //             'type' => 'FILES', 
    //             'width' => 80, 
    //             'props' => NULL,
    //         ])
            
    //         ->map(function($item) use ($children) {

    //             $item = collect($item)->only(['id', 'order_no', 'is_group', 'group_id', 'caption', 'type', 'width', 'props'])->toArray();

    //             $parent_id = $item['id'];

    //             return [
    //                 ...$item, 
    //                 'children' => $children->filter( function($child) use ($parent_id) {
    //                     return $child['group_id'] == $parent_id;
    //                 })->sortBy('order_no')->values()->toArray(),
    //             ];

    //         })

    //         ->filter( function($item) {
    //             return ! $item['group_id'];
    //         })
            
    //         ->sortBy('order_no');

    //     return $sorted->values()->toArray();

    // }


    public function rowvalues() {
        return $this->hasMany(CustomerCentralizatorRowValue::class, 'row_id')->select(['id', 'row_id', 'column_id', 'value']);
    }

    public static function setRowsVisibility($input) {
        dd(__METHOD__, $input);
    }

    // public static function doInsert($input, $record) {


    //     $coloane = CentralizatorColoana::where('centralizator_id', $input['centralizator_id'])->get()->toArray();

    //     $input = [
    //         ...$input,
    //         'current_columns' => $coloane,
    //     ];

    //     $record = self::create($input);

    //     return $record;
    
    // }

}