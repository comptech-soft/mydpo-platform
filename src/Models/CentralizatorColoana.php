<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class CentralizatorColoana extends Model {

    use Itemable, Actionable;
    
    protected $table = 'centralizatoare-columns';

    protected $casts = [
        'props' => 'json',
        'centralizator_id' => 'integer',
        'order_no' => 'integer',
        'is_group' => 'integer',
        'group_id' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'centralizator_id',
        'order_no',
        'is_group',
        'group_id',
        'slug',
        'caption',
        'type',
        'width',
        'deleted',
        'props',
        'created_by',
        'updated_by'
    ];

    // public static function reorderColumns($input) {
    //     return (new ReorderColumns($input))
    //         ->Perform();
    // }

    public static function doInsert($input, $record) {

        $input = [
            ...$input,
            'slug' => $input['centralizator_id'] . \Str::slug(md5(time())),
        ];

        

        if($input['is_group'] == 1)
        {
            $input = [
                ...$input,
                'order_no' => $input['order_no'],
                'width' => NULL,
                'type' => NULL,
            ];

        }
        else
        {
            $input = [
                ...$input,
                'order_no' => $input['order_no'],
            ];
        }

        $record = self::create($input);

        return $record;
    }

    
    public static function doDelete($input, $record) {
        dd($input, $record->id);
        
        return $record;
    }


    // public static function doUpdate($input, $record) {
    //     $record->update($input);
    //     return $record;
    // }

    // public static function doAction($action, $input) {
    //     return (new DoAction($action, $input, __CLASS__))->Perform();
    // }

    // public static function getNextOrderNo($register_id) {
    //     $records = \DB::select("
    //         SELECT 
    //             MAX(CAST(`order_no` AS SIGNED)) as max_order_no 
    //         FROM `registers-columns` 
    //         WHERE (register_id=" . $register_id . ') AND ( (is_group = 1) OR (group_id IS NULL))'
    //     );
    //     if($records[0]->max_order_no < 0)
    //     {
    //         $records[0]->max_order_no = 0;
    //     }
    //     return number_format(1 + $records[0]->max_order_no, 0, '', '');
    // }

    // public static function getNextGroupOrderNo($group_id) {
    //     $records = \DB::select("
    //         SELECT MAX(CAST(`order_no` AS SIGNED)) as max_order_no 
    //         FROM `registers-columns` WHERE (group_id=" . $group_id . ')'
    //     );
    //     if($records[0]->max_order_no < 0)
    //     {
    //         $records[0]->max_order_no = 0;
    //     }
    //     return number_format(1 + $records[0]->max_order_no, 0, '', '');
    // }

}