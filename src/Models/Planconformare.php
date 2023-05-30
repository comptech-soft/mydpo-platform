<?php

namespace MyDpo\Models;

use Illuminate\Database\Eloquent\Model;
use MyDpo\Traits\Itemable;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Actionable;
use MyDpo\Traits\NextNumber;
use MyDpo\Traits\Reorderable;

class Planconformare extends Model {

    use Itemable, Actionable, NextNumber, Reorderable, NodeTrait;

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
        'type',
        'frecventa',
        'responsabil',
        'pondere',
        'props',
        'created_by',
        'updated_by',
    ];

    public $nextNumberColumn = 'order_no';

    protected $with = [
        'children',
    ];

    protected $appends = [
        'procent_pondere',
    ];

    public function getProcentPondereAttribute() {

        if( !! $this->children()->count() )
        {
            $sum = 0;
            foreach($this->children as $i => $child)
            {
                $sum += $child->procent_pondere;
            }
            return $sum;
        }

        return $this->pondere;
    }

    public static function doInsert($input, $record) {

        if(! $input['parent_id'])
        {
            $record = self::create($input); 
        }
        else
        {
            $parent = self::find($input['parent_id']);
            $record = $parent->children()->create($input);
        }

        return self::find($record->id);
    }

    public static function PrepareActionInput($action, $input) {

        if($action == 'insert')
        {
            $input['order_no'] = !! $input['order_no'] ? $input['order_no'] : self::GetNextFieldNumber([]); 
            $input['pondere'] = !! $input['pondere'] ? $input['pondere'] : 0; 
        }

        return $input;
    }

}