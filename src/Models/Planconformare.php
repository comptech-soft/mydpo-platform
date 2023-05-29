<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Actionable;
// use MyDpo\Models\Category;
// use MyDpo\Scopes\NotdeletedScope;
// use MyDpo\Performers\Centralizator\SaveCustomerAsociere;

class Planconformare extends Model {

    use Itemable, Actionable, NodeTrait;

    protected $table = 'plan-conformare';
    
    protected $casts = [
        'id' => 'integer',
        'order_no' => 'integer',
        'props' => 'json',
        'status' => 'json',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $fillable = [
        'id',
        'actiune',
        'order_no',
        'props',
        'created_by',
        'updated_by',
    ];

    public static function doInsert($input, $record) {

        if(! $input['parent_id'])
        {
            $record = self::create($input); 
        }

        return self::find($record->id);
    }

    public static function PrepareActionInput($action, $input) {

        dd($action, $input);
    }

}