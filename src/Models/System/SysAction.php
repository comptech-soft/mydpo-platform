<?php

namespace MyDpo\Models\System;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;
use MyDpo\Traits\Itemable;
use MyDpo\Traits\Actionable;

class SysAction extends Model {

    use NodeTrait, Itemable, Actionable;
    
    protected $table = 'system-actions';

    protected $fillable = [
        'id',
        'slug',
        'type',
        'platform',
        'order_no',
        'caption',
        'description',
        'props',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'id' => 'integer',
        'platform' => 'json',
        'props' => 'json',
        'order_no' => 'integer',
        'created_by' => 'integer',
        'updated_by' => 'integer',
    ];

    protected $appends = [
        'name',
    ];

    protected $with = [
        'children',
        'roles.role',
    ];

    public function getNameAttribute() {
        return $this->caption;
    }

    function roles() {
        return $this->hasMany(SysActionRole::class, 'action_id');
    }

    public static function GetBySlug($slug) {
        return self::whereSlug($slug)->first();
    }

    public static function doInsert($input, $record) {

        if(! $input['parent_id'] )
        {
            $record = self::create($input);
        }
        else
        {
            $parent = self::find( $input['parent_id'] );
            $record = $parent->children()->create($input);
        }

        return self::find($record->id);
    }

    public static function doSettingrolesvisibility($input, $record) {

       
        $action = self::find($input['action_id']);

        $result = $action->Settingrolesvisibility($input);

        foreach($action->children as $i => $child)
        {
            $input = [
                ...$input,
                'action_id' => $child->id,
            ];

            self::doSettingrolesvisibility($input, $record);
        }

        return $result;
        
    }

    public function Settingrolesvisibility($input) {

        $r = [];
        foreach($input['roles'] as $i => $item)
        {
            
            foreach(collect($item)->except(['role_id', 'slug'])->toArray() as $key => $data)
            {
                $input = [
                    'action_id' => $input['action_id'],
                    'role_id' => $item['role_id'],
                    'platform' => $key,
                    'visible' => $data['visible'],
                    'disabled' => $data['disabled'],
                ];

                $r[] = SysActionRole::CreateOrUpdate($input);
            }
        }

        return $r;
    }
}