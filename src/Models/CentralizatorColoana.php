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

    public static function GetQuery() {
        return self::query()
            ->whereraw("( (`centralizatoare-columns`.`type` NOT IN ('VISIBILITY', 'STATUS', 'DEPARTMENT')) OR (`centralizatoare-columns`.`type` IS NULL))");
    }

    public static function doInsert($input, $record) {

        $input = [
            ...$input,
            'slug' => md5(time()),
        ];

        if( ! array_key_exists('props', $input) )
        {
            $input['props'] = NULL;
        }

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

    public static function doUpdate($input, $record) {

        if( ! array_key_exists('props', $input) )
        {
            $input['props'] = NULL;
        }

        $record->update($input);

        return $record;
    }

    public static function doDelete($input, $record) {

        self::where('group_id', $record->id)->delete();
        
        $record->delete();
        
        return $record;
        
    }

}