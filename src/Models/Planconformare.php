<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\NextNumber;

class Planconformare extends Model {

    use Itemable, Actionable, NextNumber, NodeTrait;

    protected $table = 'plan-conformare';
    
    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'props' => 'json',
        'status' => 'json',
        'pondere' => 'decimal:2',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'actiune',
        'order_no',
        'pondere',
        'props',
        'created_by',
        'updated_by',
    ];

    public $nextNumberColumn = 'order_no';

    public static function doInsert($input, $record) {

        if(! $input['parent_id'])
        {
            $record = self::create($input); 
        }

        return self::find($record->id);
    }

    public static function PrepareActionInput($action, $input) {

        if($action == 'insert')
        {
            $input['order_no'] = !! $input['order_no'] ? $input['order_no'] : self::GetNextFieldNumber([]);   
        }

        return $input;
    }

}