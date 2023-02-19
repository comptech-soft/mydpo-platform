<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
// use MyDpo\Helpers\Performers\Datatable\GetItems;
use MyDpo\Helpers\Performers\Datatable\DoAction;

class RegistruColoana extends Model {

    protected $table = 'registers-columns';

    protected $casts = [
        'props' => 'json',
        'register_id' => 'integer',
        'order_no' => 'integer',
        'is_group' => 'integer',
        'group_id' => 'integer',
        'deleted' => 'integer',
    ];

    protected $fillable = [
        'id',
        'register_id',
        'order_no',
        'is_group',
        'group_id',
        'slug',
        'caption',
        'type',
        'width',
        'decimals',
        'deleted',
        'suffix',
        'props',
        'created_by',
        'updated_by'
    ];

    public static function doInsert($input, $record) {
        if($input['column_type'] == 'group')
        {
            $input = [
                ...$input,
                'slug' => $input['register_id'] . \Str::slug(md5(time())),
                'is_group' => 1,
                'order_no' => self::getNextOrderNo($input['register_id']),
            ];

            dd($input);
        }
    }

    public static function doAction($action, $input) {
        return (new DoAction($action, $input, __CLASS__))->Perform();
    }

    public static function getNextOrderNo($register_id) {
        $records = \DB::select("
            SELECT 
                MAX(CAST(`order_no` AS UNSIGNED)) as max_order_no 
            FROM `registers-columns` 
            WHERE (register_id=" . $register_id . ') AND ( (is_group = 1) OR (group_id IS NULL))'
        );
        return number_format(1 + $records[0]->max_order_no, 0, '', '');
    }

}